<?php

use App\Enums\LearningSessionLogType;
use App\Services\LearningDurationService;

describe('buildActiveIntervals', function () {
    it('returns empty array for empty input', function() {
        $service = new LearningDurationService();

        expect($service->buildActiveIntervals([]))->toBe([]);
    });

    it('returns one interval for one start & pause logs', function() {
        $service = new LearningDurationService();

        $logs = [
            (object) ['type' => LearningSessionLogType::START, 'occurred_at' => '2026-09-08 10:00:00'],
            (object) ['type' => LearningSessionLogType::PAUSE, 'occurred_at' => '2026-09-08 10:30:00']
        ];

        expect(count($service->buildActiveIntervals($logs)))->toBe(1);
    });

    it('returns one interval for one start & stop logs', function() {
        $service = new LearningDurationService();

        $logs = [
            (object) ['type' => LearningSessionLogType::START, 'occurred_at' => '2026-09-08 11:00:00'],
            (object) ['type' => LearningSessionLogType::STOP, 'occurred_at' => '2026-09-08 11:30:00']
        ];

        expect(count($service->buildActiveIntervals($logs)))->toBe(1);
    });

    it('returns two intervals for start-pause-resume-pause logs', function() {
        $service = new LearningDurationService();

        $logs = [
            (object) ['type' => LearningSessionLogType::START, 'occurred_at' => '2026-09-08 11:00:00'],
            (object) ['type' => LearningSessionLogType::PAUSE, 'occurred_at' => '2026-09-08 11:30:00'],
            (object) ['type' => LearningSessionLogType::RESUME, 'occurred_at' => '2026-09-08 11:31:00'],
            (object) ['type' => LearningSessionLogType::PAUSE, 'occurred_at' => '2026-09-08 12:00:00']
        ];

        expect(count($service->buildActiveIntervals($logs)))->toBe(2);
    });

    it('returns two intervals for start-pause-resume-stop logs', function() {
        $service = new LearningDurationService();

        $logs = [
            (object) ['type' => LearningSessionLogType::START, 'occurred_at' => '2026-09-08 11:00:00'],
            (object) ['type' => LearningSessionLogType::PAUSE, 'occurred_at' => '2026-09-08 11:30:00'],
            (object) ['type' => LearningSessionLogType::RESUME, 'occurred_at' => '2026-09-08 11:31:00'],
            (object) ['type' => LearningSessionLogType::STOP, 'occurred_at' => '2026-09-08 12:00:00']
        ];

        expect(count($service->buildActiveIntervals($logs)))->toBe(2);
    });

    it('returns empty array for pause log with no matching start', function() {
        $service = new LearningDurationService();

        $logs = [
            (object) ['type' => LearningSessionLogType::PAUSE, 'occurred_at' => '2026-09-08 11:00:00'],
        ];

        expect($service->buildActiveIntervals($logs))->toBe([]);
    });

    it('returns empty array start-pause log with no matching start', function() {
        $service = new LearningDurationService();

        $logs = [
            (object) ['type' => LearningSessionLogType::START, 'occurred_at' => '2026-09-08 11:00:00'],
            (object) ['type' => LearningSessionLogType::PAUSE, 'occurred_at' => '2026-09-08 11:30:00'],
            (object) ['type' => LearningSessionLogType::RESUME, 'occurred_at' => '2026-09-08 12:00:00'],
        ];

        expect(count($service->buildActiveIntervals($logs)))->toBe(1);
    });
});

describe('calculateDuration', function () {
    it('returns full duration for an interval within specified period', function() {
        $service = new LearningDurationService();

        $logs = [
            (object) ['type' => LearningSessionLogType::START, 'occurred_at' => '2026-09-08 11:00:00'],
            (object) ['type' => LearningSessionLogType::PAUSE, 'occurred_at' => '2026-09-08 11:30:00'],
        ];

        $intervals = $service->buildActiveIntervals($logs);
        $result = $service->calculateDuration($intervals, '2026-09-08 00:00:00', '2026-09-08 23:59:59');

        expect($result)->toBe(1800); // 30 minutes
    });

    it('does not return full duration for an interval that ends after the specified period', function() {
        $service = new LearningDurationService();

        $logs = [
            (object) ['type' => LearningSessionLogType::START, 'occurred_at' => '2026-09-08 23:29:59'],
            (object) ['type' => LearningSessionLogType::PAUSE, 'occurred_at' => '2026-09-09 00:05:00'],
        ];

        $intervals = $service->buildActiveIntervals($logs);
        $result = $service->calculateDuration($intervals, '2026-09-08 00:00:00', '2026-09-08 23:59:59');

        expect($result)->toBe(1800); // 30 minutes -> overlapped 5 minutes
    });

    it('does not return full duration for an interval that starts before the specified period', function() {
        $service = new LearningDurationService();

        $logs = [
            (object) ['type' => LearningSessionLogType::START, 'occurred_at' => '2026-09-07 23:55:00'],
            (object) ['type' => LearningSessionLogType::PAUSE, 'occurred_at' => '2026-09-08 00:30:00'],
        ];

        $intervals = $service->buildActiveIntervals($logs);
        $result = $service->calculateDuration($intervals, '2026-09-08 00:00:00', '2026-09-08 23:59:59');

        expect($result)->toBe(1800); // 30 minutes -> overlapped 5 minutes
    });

    it('does not return full duration for an interval that starts before and ends after the specified period', function() {
        $service = new LearningDurationService();

        $logs = [
            (object) ['type' => LearningSessionLogType::START, 'occurred_at' => '2026-09-07 23:55:00'],
            (object) ['type' => LearningSessionLogType::PAUSE, 'occurred_at' => '2026-09-09 00:05:00'],
        ];

        $intervals = $service->buildActiveIntervals($logs);
        $result = $service->calculateDuration($intervals, '2026-09-08 00:00:00', '2026-09-08 23:59:59');

        expect($result)->not->toBe(1800); // 30 minutes -> overlapped 5 minutes
    });

    it('returns zero on duration before the specified period', function() {
        $service = new LearningDurationService();

        $logs = [
            (object) ['type' => LearningSessionLogType::START, 'occurred_at' => '2026-09-07 20:00:00'],
            (object) ['type' => LearningSessionLogType::PAUSE, 'occurred_at' => '2026-09-07 22:00:00'],
        ];

        $intervals = $service->buildActiveIntervals($logs);
        $result = $service->calculateDuration($intervals, '2026-09-08 00:00:00', '2026-09-08 23:59:59');

        expect($result)->not->toBe(1800); // 30 minutes -> overlapped 5 minutes
    });

    it('returns expected duration for multiple intervals with partial overlaps', function() {
        $service = new LearningDurationService();

        $logs = [
            (object) ['type' => LearningSessionLogType::START, 'occurred_at' => '2026-09-07 23:30:00'],
            (object) ['type' => LearningSessionLogType::PAUSE, 'occurred_at' => '2026-09-07 00:30:00'],
            (object) ['type' => LearningSessionLogType::RESUME, 'occurred_at' => '2026-09-08 23:30:00'],
            (object) ['type' => LearningSessionLogType::PAUSE, 'occurred_at' => '2026-09-09 00:30:00'],
        ];

        $intervals = $service->buildActiveIntervals($logs);
        $result = $service->calculateDuration($intervals, '2026-09-08 00:00:00', '2026-09-08 23:59:59');

        expect($result)->not->toBe(3599); // 30 minutes -> overlapped 5 minutes
    });

    it('returns zero when no logs found', function() {
        $service = new LearningDurationService();

        $result = $service->calculateDuration([], '2026-09-08 00:00:00', '2026-09-08 23:59:59');

        expect($result)->toBe(0);
    });
});
