<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Group;
use App\Models\Seance;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    /**
     * Show the attendance sheet for a given session.
     */
    public function edit(Group $group, Seance $seance)
    {
        abort_unless($seance->group_id === $group->id, 404);

        $students = $group->students()->orderBy('name')->get();

        $existing = Attendance::where('seance_id', $seance->id)
            ->get()
            ->keyBy('student_id');

        return view('seances.attendance', compact('group', 'seance', 'students', 'existing'));
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