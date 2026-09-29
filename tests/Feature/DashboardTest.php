<?php

use App\Models\Learning;
use App\Models\LearningSession;
use App\Models\User;
use App\Services\DashboardService;

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
