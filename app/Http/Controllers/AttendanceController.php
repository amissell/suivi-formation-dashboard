<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Group;
use App\Models\Seance;
use App\Models\Student;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function edit(Group $group, Seance $seance)
    {
        abort_unless($seance->group_id === $group->id, 404);

        $students = $group->students()->orderBy('name')->get();

        $existing = Attendance::where('seance_id', $seance->id)
            ->get()
            ->keyBy('student_id');

        return view('seances.attendance', compact('group', 'seance', 'students', 'existing'));
    }

    /** Show one student's attendance totals and session-by-session history. */
    public function history(Group $group, Student $student)
    {
        abort_unless($group->students()->whereKey($student->id)->exists(), 404);

        $history = Attendance::with('seance')
            ->where('student_id', $student->id)
            ->whereHas('seance', fn ($query) => $query->where('group_id', $group->id))
            ->get()
            ->sortByDesc(fn ($attendance) => $attendance->seance->date_seance)
            ->values();

        $totals = [
            'present' => $history->where('status', 'present')->count(),
            'absent' => $history->where('status', 'absent')->count(),
            'late' => $history->where('status', 'late')->count(),
            'recorded' => $history->count(),
            'sessions' => $group->seances()->where('statut', '!=', 'annulée')->count(),
        ];

        return view('students.attendance-history', compact('group', 'student', 'history', 'totals'));
    }

    /**
     * Save attendance statuses for a session.
     */
    public function update(Request $request, Group $group, Seance $seance)
    {
        abort_unless($seance->group_id === $group->id, 404);

        $data = $request->validate([
            'statuses'           => 'required|array',
            'statuses.*'         => 'required|in:present,absent,late',
        ]);

        foreach ($data['statuses'] as $studentId => $status) {
            // Ensure the student actually belongs to this group
            if (!$group->students()->where('students.id', $studentId)->exists()) {
                continue;
            }

            Attendance::updateOrCreate(
                ['seance_id' => $seance->id, 'student_id' => $studentId],
                ['status' => $status]
            );
        }

        return redirect()
            ->route('groups.show', $group)
            ->with('success', 'Présences enregistrées pour "' . $seance->titre . '"');
    }
}
