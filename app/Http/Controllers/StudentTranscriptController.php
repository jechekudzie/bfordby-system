<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Module;
use App\Models\Enrollment;
use App\Models\Subject;
use App\Models\Semester;
use App\Models\Assessment;
use App\Models\AssessmentAllocation;
use App\Models\AssessmentAllocationSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class StudentTranscriptController extends Controller
{
    /**
     * Display the student's academic transcript
     */
    public function show(Request $request, Student $student = null)
    {
        // If no student is provided, get the first student
        if (!$student || !$student->exists) {
            $student = Student::first();
            
            // If there are no students, redirect to the students list
            if (!$student) {
                return redirect()->route('students.index')
                    ->with('error', 'No student found. Please create a student first.');
            }
        }
        
        // Get active enrollments for the student
        $enrollments = $student->enrollments()->where('status', 'active')->get();
        
        // Get enrollment code IDs for this student's enrollments
        $enrollmentCodeIds = $enrollments->pluck('enrollment_code_id')->unique();
        
        // Get all semesters for this student's enrollment codes via assessment allocations
        $semesters = Semester::whereHas('assessmentAllocations', function($query) use ($enrollmentCodeIds) {
            $query->whereIn('enrollment_code_id', $enrollmentCodeIds);
        })->orderBy('academic_year')->orderBy('name')->get();
        
        // Structure to hold all transcript data
        $transcriptData = [];
        
        foreach ($semesters as $semester) {
            $trimesterData = [
                'semester' => $semester,
                'subjects' => []
            ];
            
            // Get all courses for this student's enrollments
            $courseIds = $enrollments->pluck('course_id')->unique();
            
            // Get subjects for these courses
            $subjects = Subject::whereIn('course_id', $courseIds)->get();
            
            foreach ($subjects as $subject) {
                $subjectData = [
                    'subject' => $subject,
                    'modules' => []
                ];
                
                // Get modules for this subject
                $modules = $subject->modules;
                
                foreach ($modules as $module) {
                    $moduleData = [
                        'module' => $module,
                        'assessments' => [],
                        'grade' => $module->calculateTrimesterGrade($student->id, $semester->id),
                        'grade_classification' => $module->getGradeClassification(
                            $module->calculateTrimesterGrade($student->id, $semester->id)
                        )
                    ];
                    
                    // Get assessments for this module
                    $assessments = $module->assessments()
                        ->whereHas('allocations', function($query) use ($semester, $enrollmentCodeIds) {
                            $query->where('semester_id', $semester->id)
                                  ->whereIn('enrollment_code_id', $enrollmentCodeIds);
                        })
                        ->get();
                    
                    foreach ($assessments as $assessment) {
                        // Get the allocation for this assessment in this semester
                        $allocation = $assessment->allocations()
                            ->where('semester_id', $semester->id)
                            ->whereIn('enrollment_code_id', $enrollmentCodeIds)
                            ->first();
                        
                        if (!$allocation) {
                            continue;
                        }
                        
                        // Get the student's submission for this allocation
                        $submission = $allocation->submissions()
                            ->where('student_id', $student->id)
                            ->first();
                        
                        $assessmentData = [
                            'assessment' => $assessment,
                            'allocation' => $allocation,
                            'submission' => $submission,
                            'submitted' => $submission && $submission->submitted_at,
                            'graded' => $submission && $submission->status === 'graded',
                            'grade' => $submission ? $submission->grade : null,
                            'weighted_grade' => $assessment->calculateWeightedGrade($student->id, $semester->id)
                        ];
                        
                        $moduleData['assessments'][] = $assessmentData;
                    }
                    
                    $subjectData['modules'][] = $moduleData;
                }
                
                // Only add subject if it has modules with assessments
                if (collect($subjectData['modules'])->flatMap(function($module) {
                    return $module['assessments'];
                })->isNotEmpty()) {
                    $trimesterData['subjects'][] = $subjectData;
                }
            }
            
            // Only add trimester if it has subjects with modules and assessments
            if (!empty($trimesterData['subjects'])) {
                $transcriptData[] = $trimesterData;
            }
        }
        
        return view('students.transcript.show', compact('student', 'transcriptData'));
    }

    /**
     * Download the student's transcript as PDF
     */
    public function download(Request $request, Student $student = null)
    {
        // If no student is provided, get the first student
        if (!$student || !$student->exists) {
            $student = Student::first();
            
            if (!$student) {
                return redirect()->route('students.index')
                    ->with('error', 'No student found. Please create a student first.');
            }
        }

        // Get active enrollments for the student
        $enrollments = $student->enrollments()->where('status', 'active')->get();
        
        // Get enrollment code IDs for this student's enrollments
        $enrollmentCodeIds = $enrollments->pluck('enrollment_code_id')->unique();
        
        // Get all semesters for this student's enrollment codes via assessment allocations
        $semesters = Semester::whereHas('assessmentAllocations', function($query) use ($enrollmentCodeIds) {
            $query->whereIn('enrollment_code_id', $enrollmentCodeIds);
        })->orderBy('academic_year')->orderBy('name')->get();
        
        // Structure to hold all transcript data
        $transcriptData = [];
        
        foreach ($semesters as $semester) {
            $trimesterData = [
                'semester' => $semester,
                'subjects' => []
            ];
            
            // Get all courses for this student's enrollments
            $courseIds = $enrollments->pluck('course_id')->unique();
            
            // Get subjects for these courses
            $subjects = Subject::whereIn('course_id', $courseIds)->get();
            
            foreach ($subjects as $subject) {
                $subjectData = [
                    'subject' => $subject,
                    'modules' => []
                ];
                
                // Get modules for this subject
                $modules = $subject->modules;
                
                foreach ($modules as $module) {
                    $moduleData = [
                        'module' => $module,
                        'assessments' => [],
                        'grade' => $module->calculateTrimesterGrade($student->id, $semester->id),
                        'grade_classification' => $module->getGradeClassification(
                            $module->calculateTrimesterGrade($student->id, $semester->id)
                        )
                    ];
                    
                    // Get assessments for this module
                    $assessments = $module->assessments()
                        ->whereHas('allocations', function($query) use ($semester, $enrollmentCodeIds) {
                            $query->where('semester_id', $semester->id)
                                  ->whereIn('enrollment_code_id', $enrollmentCodeIds);
                        })
                        ->get();
                    
                    foreach ($assessments as $assessment) {
                        // Get the allocation for this assessment in this semester
                        $allocation = $assessment->allocations()
                            ->where('semester_id', $semester->id)
                            ->whereIn('enrollment_code_id', $enrollmentCodeIds)
                            ->first();
                        
                        if (!$allocation) {
                            continue;
                        }
                        
                        // Get the student's submission for this allocation
                        $submission = $allocation->submissions()
                            ->where('student_id', $student->id)
                            ->first();
                        
                        $assessmentData = [
                            'assessment' => $assessment,
                            'allocation' => $allocation,
                            'submission' => $submission,
                            'submitted' => $submission && $submission->submitted_at,
                            'graded' => $submission && $submission->status === 'graded',
                            'grade' => $submission ? $submission->grade : null,
                            'weighted_grade' => $assessment->calculateWeightedGrade($student->id, $semester->id)
                        ];
                        
                        $moduleData['assessments'][] = $assessmentData;
                    }
                    
                    $subjectData['modules'][] = $moduleData;
                }
                
                // Only add subject if it has modules with assessments
                if (collect($subjectData['modules'])->flatMap(function($module) {
                    return $module['assessments'];
                })->isNotEmpty()) {
                    $trimesterData['subjects'][] = $subjectData;
                }
            }
            
            // Only add trimester if it has subjects with modules and assessments
            if (!empty($trimesterData['subjects'])) {
                $transcriptData[] = $trimesterData;
            }
        }
        
        // Generate PDF
        $pdf = PDF::loadView('students.transcript.pdf', [
            'student' => $student,
            'transcriptData' => $transcriptData,
            'date' => date('F j, Y')
        ]);
        
        // Set PDF options
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOptions([
            'defaultFont' => 'sans-serif',
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true
        ]);
        
        // Generate a filename
        $filename = 'transcript_' . $student->first_name . '_' . $student->last_name . '_' . date('Y-m-d') . '.pdf';
        
        // Download the PDF
        return $pdf->download($filename);
    }

    public function simplified(Student $student = null)
    {
        // If no student is provided, get the first student
        if (!$student || !$student->exists) {
            $student = Student::first();
            
            // If there are no students, redirect to the students list
            if (!$student) {
                return redirect()->route('students.index')
                    ->with('error', 'No student found. Please create a student first.');
            }
        }
        
        // Get active enrollments for the student
        $enrollments = $student->enrollments()->where('status', 'active')->get();
        
        if ($enrollments->isEmpty()) {
            return redirect()->route('students.index')->with('error', 'No active enrollments found for this student.');
        }
        
        // Get enrollment code IDs for this student's enrollments
        $enrollmentCodeIds = $enrollments->pluck('enrollment_code_id')->unique();
        
        // Get all semesters for this student's enrollment codes via assessment allocations
        $semesters = Semester::whereHas('assessmentAllocations', function($query) use ($enrollmentCodeIds) {
            $query->whereIn('enrollment_code_id', $enrollmentCodeIds);
        })->orderBy('academic_year')->orderBy('name')->get();
        
        // Structure to hold all transcript data
        $transcriptData = [];
        
        foreach ($semesters as $semester) {
            $trimesterData = [
                'semester' => $semester,
                'subjects' => []
            ];
            
            // Get all courses for this student's enrollments
            $courseIds = $enrollments->pluck('course_id')->unique();
            
            // Get subjects for these courses
            $subjects = Subject::whereIn('course_id', $courseIds)->get();
            
            foreach ($subjects as $subject) {
                $subjectData = [
                    'subject' => $subject,
                    'modules' => []
                ];
                
                // Get modules for this subject
                $modules = $subject->modules;
                
                foreach ($modules as $module) {
                    $moduleData = [
                        'module' => $module,
                        'grade' => $module->calculateTrimesterGrade($student->id, $semester->id),
                        'grade_classification' => $module->getGradeClassification(
                            $module->calculateTrimesterGrade($student->id, $semester->id)
                        )
                    ];
                    
                    // Only check if there are assessments for this module in this semester
                    $hasAssessments = $module->assessments()
                        ->whereHas('allocations', function($query) use ($semester, $enrollmentCodeIds) {
                            $query->where('semester_id', $semester->id)
                                  ->whereIn('enrollment_code_id', $enrollmentCodeIds);
                        })
                        ->exists();
                    
                    // Only add module if it has assessments
                    if ($hasAssessments) {
                        $subjectData['modules'][] = $moduleData;
                    }
                }
                
                // Only add subject if it has modules with assessments
                if (!empty($subjectData['modules'])) {
                    $trimesterData['subjects'][] = $subjectData;
                }
            }
            
            // Only add trimester if it has subjects with modules and assessments
            if (!empty($trimesterData['subjects'])) {
                $transcriptData[] = $trimesterData;
            }
        }
        
        return view('students.transcript.simplified', compact('student', 'transcriptData'));
    }

    /**
     * Download the student's simplified transcript as PDF
     */
    public function downloadSimplified(Request $request, Student $student = null)
    {
        // If no student is provided, get the first student
        if (!$student || !$student->exists) {
            $student = Student::first();
            
            if (!$student) {
                return redirect()->route('students.index')
                    ->with('error', 'No student found. Please create a student first.');
            }
        }

        // Get active enrollments for the student
        $enrollments = $student->enrollments()->where('status', 'active')->get();
        
        if ($enrollments->isEmpty()) {
            return redirect()->route('students.index')->with('error', 'No active enrollments found for this student.');
        }
        
        // Get enrollment code IDs for this student's enrollments
        $enrollmentCodeIds = $enrollments->pluck('enrollment_code_id')->unique();
        
        // Get all semesters for this student's enrollment codes via assessment allocations
        $semesters = Semester::whereHas('assessmentAllocations', function($query) use ($enrollmentCodeIds) {
            $query->whereIn('enrollment_code_id', $enrollmentCodeIds);
        })->orderBy('academic_year')->orderBy('name')->get();
        
        // Structure to hold all transcript data
        $transcriptData = [];
        
        foreach ($semesters as $semester) {
            $trimesterData = [
                'semester' => $semester,
                'subjects' => []
            ];
            
            // Get all courses for this student's enrollments
            $courseIds = $enrollments->pluck('course_id')->unique();
            
            // Get subjects for these courses
            $subjects = Subject::whereIn('course_id', $courseIds)->get();
            
            foreach ($subjects as $subject) {
                $subjectData = [
                    'subject' => $subject,
                    'modules' => []
                ];
                
                // Get modules for this subject
                $modules = $subject->modules;
                
                foreach ($modules as $module) {
                    $moduleData = [
                        'module' => $module,
                        'grade' => $module->calculateTrimesterGrade($student->id, $semester->id),
                        'grade_classification' => $module->getGradeClassification(
                            $module->calculateTrimesterGrade($student->id, $semester->id)
                        )
                    ];
                    
                    // Only check if there are assessments for this module in this semester
                    $hasAssessments = $module->assessments()
                        ->whereHas('allocations', function($query) use ($semester, $enrollmentCodeIds) {
                            $query->where('semester_id', $semester->id)
                                  ->whereIn('enrollment_code_id', $enrollmentCodeIds);
                        })
                        ->exists();
                    
                    // Only add module if it has assessments
                    if ($hasAssessments) {
                        $subjectData['modules'][] = $moduleData;
                    }
                }
                
                // Only add subject if it has modules with assessments
                if (!empty($subjectData['modules'])) {
                    $trimesterData['subjects'][] = $subjectData;
                }
            }
            
            // Only add trimester if it has subjects with modules and assessments
            if (!empty($trimesterData['subjects'])) {
                $transcriptData[] = $trimesterData;
            }
        }

        $date = date('F j, Y');
        $filename = 'simplified_transcript_' . $student->student_number . '.pdf';
        
        $pdf = Pdf::loadView('students.transcript.pdf-simplified', compact('student', 'transcriptData', 'date'));
        
        return $pdf->download($filename);
    }
} 