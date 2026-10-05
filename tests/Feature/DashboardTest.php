<?php

use App\Enums\LearningSessionLogType;
use App\Models\Learning;
use App\Models\LearningSession;
use App\Models\LearningSessionLog;
use App\Models\User;
use App\Services\DashboardService;
use Carbon\Carbon;

// test('guests are redirected to the login page', function () {
//     $response = $this->get('/dashboard');
//     $response->assertRedirect('/login');
// });

// test('authenticated users can visit the dashboard', function () {
//     $user = User::factory()->create();
//     $this->actingAs($user);

//     $response = $this->get('/dashboard');
//     $response->assertStatus(200);
// });

describe('getSkillBreakdown', function() {
    it('returns correct array attributes', function() {
        $user = User::factory()->create();
        $this->actingAs($user);

        $learning = Learning::factory()->for($user)->create();
        $session = LearningSession::factory()->for($learning)->create(['total_duration' => 3600]);

        $service = new DashboardService();

        $result = $service->getSkillBreakdown();

        $requiredKeys = ['id', 'name', 'total_duration', 'duration_formatted'];

        $allExist = array_all($requiredKeys, fn($key) => array_key_exists($key, $result[0]));

        expect($allExist)->toBeBool()->toBe(true);
    });

    it('returns accurate duration', function() {
        $user = User::factory()->create();
        $this->actingAs($user);

        $learning = Learning::factory()->for($user)->create();
        $session = LearningSession::factory()->for($learning)->create(['total_duration' => 3600]);

        $service = new DashboardService();

        $result = $service->getSkillBreakdown();

        expect($result[0]['total_duration'])->toBeInt()->toBe(3600);
    });

    it('returns accurate formatted duration', function() {
        $user = User::factory()->create();
        $this->actingAs($user);

        $learnings = Learning::factory()->for($user)->create();
        $sessions = LearningSession::factory()->for($learnings)->createMany([
            [ 'total_duration' => 3600 ],
            [ 'total_duration' => 3900 ],
            [ 'total_duration' => 3950 ],
        ]);

        $service = new DashboardService();
        $result = $service->getSkillBreakdown();

        // expect($result[0]['duration_formatted'])->toBe('1 hour');
        // expect($result[1]['duration_formatted'])->toBe('2 hours 5 minutes');
        expect($result[0]['duration_formatted'])->toBe('3 hours 10 minutes 50 seconds');
    });

    it('orders duration in descending order', function() {
        $user = User::factory()->create();
        $this->actingAs($user);

        $durations = [ 6000, 5000, 4000, 3000, 2000, 1000 ];

        foreach ($durations as $duration) {
            $learning = Learning::factory()->for($user)->create();
            LearningSession::factory()->for($learning)->create(['total_duration' => $duration]);
        }

        $service = new DashboardService();

        $result = $service->getSkillBreakdown();

        $itemsAreSorted = collect($result)
            ->sliding(2)
            ->every(fn($pair) => $pair->first()['total_duration'] >= $pair->last()['total_duration']);

        expect($itemsAreSorted)->toBe(true);
    });

    it('excludes learnings with no sessions', function() {
        $user = User::factory()->create();
        $this->actingAs($user);

        $learningWithSessions = Learning::factory()->for($user)->create();
        LearningSession::factory()->for($learningWithSessions)->create(['total_duration' => 3600]);

        $learningWithoutSessions = Learning::factory()->for($user)->create();

        $service = new DashboardService();

        $result = $service->getSkillBreakdown();

        expect($result)->toHaveCount(1);
        expect(array_column($result, 'id'))->not->toContain($learningWithoutSessions->id);
    });

    it('returns empty array on empty state', function() {
        $user = User::factory()->create();
        $this->actingAs($user);

        $service = new DashboardService();

        $result = $service->getSkillBreakdown();

        expect($result)->toBe([]);
    });
});

describe('getWeeklyActivity', function() {
    // Pins "now" to Wednesday 2026-09-30 so the current week is always
    // Mon 2026-09-28 .. Sun 2026-10-04, no matter when the tests run.
    beforeEach(function() {
        $this->travelTo(Carbon::parse('2026-09-30 12:00:00'));
    });

    it('returns all seven days with zero when there is no activity', function() {
        $user = User::factory()->create();
        $this->actingAs($user);

        $service = new DashboardService();

        $result = $service->getWeeklyActivity();

        expect($result)->toBe([
            'monday' => 0,
            'tuesday' => 0,
            'wednesday' => 0,
            'thursday' => 0,
            'friday' => 0,
            'saturday' => 0,
            'sunday' => 0,
        ]);
    });

    it('attributes session time to the correct day', function() {
        $user = User::factory()->create();
        $this->actingAs($user);

        $learning = Learning::factory()->for($user)->create();
        $session = LearningSession::factory()->for($learning)->create([
            'started_at' => '2026-09-30 10:00:00',
            'ended_at' => '2026-09-30 10:30:00',
        ]);

        LearningSessionLog::create([
            'learning_session_id' => $session->id,
            'type' => LearningSessionLogType::START->value,
            'occurred_at' => '2026-09-30 10:00:00',
        ]);
        LearningSessionLog::create([
            'learning_session_id' => $session->id,
            'type' => LearningSessionLogType::PAUSE->value,
            'occurred_at' => '2026-09-30 10:30:00',
        ]);

        $service = new DashboardService();

        $result = $service->getWeeklyActivity();

        expect($result)->toBe([
            'monday' => 0,
            'tuesday' => 0,
            'wednesday' => 1800,
            'thursday' => 0,
            'friday' => 0,
            'saturday' => 0,
            'sunday' => 0,
        ]);
    });

    it('splits a session that crosses midnight across two days', function() {
        $user = User::factory()->create();
        $this->actingAs($user);

        $learning = Learning::factory()->for($user)->create();
        $session = LearningSession::factory()->for($learning)->create([
            'started_at' => '2026-09-28 23:00:00',
            'ended_at' => '2026-09-29 01:00:00',
        ]);

        LearningSessionLog::create([
            'learning_session_id' => $session->id,
            'type' => LearningSessionLogType::START->value,
            'occurred_at' => '2026-09-28 23:00:00',
        ]);
        LearningSessionLog::create([
            'learning_session_id' => $session->id,
            'type' => LearningSessionLogType::STOP->value,
            'occurred_at' => '2026-09-29 01:00:00',
        ]);

        $service = new DashboardService();

        $result = $service->getWeeklyActivity();

        \Log::info($result);

        expect($result)->toBe([
            'monday' => 3600,
            'tuesday' => 3600,
            'wednesday' => 0,
            'thursday' => 0,
            'friday' => 0,
            'saturday' => 0,
            'sunday' => 0,
        ]);
    });

    it('excludes sessions outside the current week', function() {
        $user = User::factory()->create();
        $this->actingAs($user);

        $learning = Learning::factory()->for($user)->create();
        $session = LearningSession::factory()->for($learning)->create([
            'started_at' => '2026-09-21 10:00:00',
            'ended_at' => '2026-09-21 11:00:00',
        ]);

        LearningSessionLog::create([
            'learning_session_id' => $session->id,
            'type' => LearningSessionLogType::START->value,
            'occurred_at' => '2026-09-21 10:00:00',
        ]);
        LearningSessionLog::create([
            'learning_session_id' => $session->id,
            'type' => LearningSessionLogType::STOP->value,
            'occurred_at' => '2026-09-21 11:00:00',
        ]);

        $service = new DashboardService();

        $result = $service->getWeeklyActivity();

        expect($result)->toBe([
            'monday' => 0,
            'tuesday' => 0,
            'wednesday' => 0,
            'thursday' => 0,
            'friday' => 0,
            'saturday' => 0,
            'sunday' => 0,
        ]);
    });

    it('excludes other users activity', function() {
        $user = User::factory()->create();
        $this->actingAs($user);

        $otherUser = User::factory()->create();
        $otherLearning = Learning::factory()->for($otherUser)->create();
        $otherSession = LearningSession::factory()->for($otherLearning)->create([
            'started_at' => '2026-09-30 09:00:00',
            'ended_at' => '2026-09-30 10:00:00',
        ]);

        LearningSessionLog::create([
            'learning_session_id' => $otherSession->id,
            'type' => LearningSessionLogType::START->value,
            'occurred_at' => '2026-09-30 09:00:00',
        ]);
        LearningSessionLog::create([
            'learning_session_id' => $otherSession->id,
            'type' => LearningSessionLogType::STOP->value,
            'occurred_at' => '2026-09-30 10:00:00',
        ]);

        $service = new DashboardService();

        $result = $service->getWeeklyActivity();

        expect($result)->toBe([
            'monday' => 0,
            'tuesday' => 0,
            'wednesday' => 0,
            'thursday' => 0,
            'friday' => 0,
            'saturday' => 0,
            'sunday' => 0,
        ]);
    });

    it('returns zero for an in-progress session', function() {
        // KNOWN LIMITATION: a dangling START (no PAUSE/STOP yet) counts as 0.
        // Tracked as Post-MVP: "Dashboard: count in-progress session time".
        $user = User::factory()->create();
        $this->actingAs($user);

        $learning = Learning::factory()->for($user)->create();
        $session = LearningSession::factory()->for($learning)->create([
            'started_at' => '2026-09-30 09:00:00',
            'ended_at' => null,
        ]);

        LearningSessionLog::create([
            'learning_session_id' => $session->id,
            'type' => LearningSessionLogType::START->value,
            'occurred_at' => '2026-09-30 09:00:00',
        ]);

        $service = new DashboardService();

        $result = $service->getWeeklyActivity();

        expect($result)->toBe([
            'monday' => 0,
            'tuesday' => 0,
            'wednesday' => 0,
            'thursday' => 0,
            'friday' => 0,
            'saturday' => 0,
            'sunday' => 0,
        ]);
    });
});
