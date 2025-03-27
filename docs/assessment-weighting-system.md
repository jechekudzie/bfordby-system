# Assessment Weighting System Documentation

## Overview
The assessment weighting system implements a hierarchical grading structure where each assessment's contribution to the final module grade is determined by:
1. The assessment type weight (e.g., Coursework 25%)
2. The trimester weight (e.g., Trimester 1 30%)

## Database Structure

### 1. Assessment Contribution Types Table
```sql
CREATE TABLE assessment_contribution_types (
    id bigint PRIMARY KEY,
    name varchar(255),        -- e.g., 'Coursework', 'Test', 'Practical', 'Theory'
    description text,
    created_at timestamp,
    updated_at timestamp
);
```

### 2. Module Assessment Structures Table
```sql
CREATE TABLE module_assessment_structures (
    id bigint PRIMARY KEY,
    module_id bigint,
    assessment_contribution_type_id bigint,
    weight decimal(5,2),      -- e.g., 25.00 for 25%
    is_trimester_weight boolean,
    trimester integer,        -- 1, 2, or 3
    created_at timestamp,
    updated_at timestamp
);
```

## Models

### 1. AssessmentContributionType
```php
class AssessmentContributionType extends Model
{
    protected $fillable = ['name', 'description'];

    public function moduleStructures()
    {
        return $this->hasMany(ModuleAssessmentStructure::class);
    }
}
```

### 2. ModuleAssessmentStructure
```php
class ModuleAssessmentStructure extends Model
{
    protected $fillable = [
        'module_id',
        'assessment_contribution_type_id',
        'weight',
        'is_trimester_weight',
        'trimester'
    ];

    // Scopes for filtering
    public function scopeAssessmentTypes($query)
    {
        return $query->where('is_trimester_weight', false);
    }

    public function scopeTrimesterWeights($query)
    {
        return $query->where('is_trimester_weight', true);
    }
}
```

### 3. Assessment Model Updates
```php
class Assessment extends Model
{
    // Get the contribution weight for this assessment type
    public function getContributionWeight()
    {
        return $this->module->assessmentStructures()
            ->where('is_trimester_weight', false)
            ->whereHas('contributionType', function($query) {
                $query->where('name', $this->type);
            })
            ->first()
            ->weight ?? 0;
    }

    // Get trimester weight for a semester
    public function getTrimesterWeight($semesterId)
    {
        $semester = Semester::find($semesterId);
        if (!$semester) return 0;

        return $this->module->assessmentStructures()
            ->where('is_trimester_weight', true)
            ->where('trimester', $semester->trimester)
            ->first()
            ->weight ?? 0;
    }

    // Calculate weighted grade
    public function calculateWeightedGrade($studentId, $semesterId)
    {
        $submission = $this->allocations()
            ->where('semester_id', $semesterId)
            ->whereHas('submissions', function($query) use ($studentId) {
                $query->where('student_id', $studentId)
                      ->whereNotNull('grade');
            })
            ->first()
            ?->submissions()
            ->where('student_id', $studentId)
            ->first();

        if (!$submission) return 0;

        $contributionWeight = $this->getContributionWeight();
        $trimesterWeight = $this->getTrimesterWeight($semesterId);
        
        return ($submission->grade * ($contributionWeight / 100) * ($trimesterWeight / 100));
    }
}
```

### 4. Module Model Updates
```php
class Module extends Model
{
    // Get assessment type weights
    public function assessmentTypeWeights()
    {
        return $this->assessmentStructures()->assessmentTypes();
    }

    // Get trimester weights
    public function trimesterWeights()
    {
        return $this->assessmentStructures()->trimesterWeights();
    }

    // Calculate trimester grade
    public function calculateTrimesterGrade($studentId, $semesterId)
    {
        $semester = Semester::find($semesterId);
        if (!$semester) return 0;

        $assessments = $this->assessments()
            ->whereHas('allocations', function($query) use ($semesterId) {
                $query->where('semester_id', $semesterId);
            })
            ->get();

        $trimesterGrade = 0;
        foreach ($assessments as $assessment) {
            $trimesterGrade += $assessment->calculateWeightedGrade($studentId, $semesterId);
        }

        return $trimesterGrade;
    }

    // Calculate final grade
    public function calculateFinalGrade($studentId, $year)
    {
        $semesters = Semester::where('year', $year)->get();
        $finalGrade = 0;

        foreach ($semesters as $semester) {
            $finalGrade += $this->calculateTrimesterGrade($studentId, $semester->id);
        }

        return $finalGrade;
    }
}
```

## Usage Examples

### 1. Setting Up Assessment Weights
```php
// Define assessment type weights for a module
$module->assessmentStructures()->createMany([
    [
        'assessment_contribution_type_id' => $courseworkType->id,
        'weight' => 25,
        'is_trimester_weight' => false
    ],
    [
        'assessment_contribution_type_id' => $practicalType->id,
        'weight' => 15,
        'is_trimester_weight' => false
    ],
    [
        'assessment_contribution_type_id' => $testType->id,
        'weight' => 10,
        'is_trimester_weight' => false
    ],
    [
        'assessment_contribution_type_id' => $theoryType->id,
        'weight' => 50,
        'is_trimester_weight' => false
    ]
]);

// Define trimester weights
$module->assessmentStructures()->createMany([
    [
        'assessment_contribution_type_id' => $courseworkType->id,
        'weight' => 30,
        'is_trimester_weight' => true,
        'trimester' => 1
    ],
    [
        'assessment_contribution_type_id' => $courseworkType->id,
        'weight' => 35,
        'is_trimester_weight' => true,
        'trimester' => 2
    ],
    [
        'assessment_contribution_type_id' => $courseworkType->id,
        'weight' => 35,
        'is_trimester_weight' => true,
        'trimester' => 3
    ]
]);
```

### 2. Calculating Grades
```php
// Get assessment's contribution to final grade
$assessment = Assessment::find($id);
$contributionWeight = $assessment->getContributionWeight();
$trimesterWeight = $assessment->getTrimesterWeight($semesterId);
$weightedGrade = $assessment->calculateWeightedGrade($studentId, $semesterId);

// Calculate trimester grade
$trimesterGrade = $module->calculateTrimesterGrade($studentId, $semesterId);

// Calculate final grade for the year
$finalGrade = $module->calculateFinalGrade($studentId, $year);
```

## Grade Calculation Example

For a student who gets 80% in a Coursework assessment in Trimester 1:

1. Raw Grade: 80%
2. Assessment Type Weight: 25% (Coursework)
3. Trimester Weight: 30% (Trimester 1)
4. Contribution Calculation:
   * 80% × (25/100) × (30/100) = 6%

This means this assessment contributes 6% to the student's final module grade.

## UI Components

### 1. Assessment Creation/Edit View
```blade
<!-- Assessment Type Selection with Weights -->
<select class="form-select" name="type" required>
    <option value="">Select Type</option>
    @foreach($assessmentWeights as $type => $weight)
        <option value="{{ $type }}">
            {{ $type }} ({{ $weight }}%)
        </option>
    @endforeach
</select>
```

### 2. Grading Interface
The grading interface shows:
1. Assessment Details:
   - Student information
   - Submission date
   - Assessment type and weight
   - Trimester weight

2. Grade Summary Card:
```blade
<div class="contribution-info">
    <h6>Grade Contribution</h6>
    <!-- Assessment Type Weight -->
    <div class="d-flex justify-content-between">
        <small>Assessment Type Weight:</small>
        <span class="badge bg-info">{{ $contributionWeight }}%</span>
    </div>
    <!-- Trimester Weight -->
    <div class="d-flex justify-content-between">
        <small>Trimester Weight:</small>
        <span class="badge bg-secondary">{{ $trimesterWeight }}%</span>
    </div>
    <!-- Final Contribution -->
    <div class="alert alert-light">
        <small>Contribution to Final Grade:</small>
        <span class="fw-bold text-primary">
            {{ number_format(($percentageGrade * $contributionWeight * $trimesterWeight) / 10000, 2) }}%
        </span>
    </div>
</div>
```

3. Real-time Grade Calculation:
```javascript
function updateTotals() {
    // Calculate raw grade percentage
    const percentageGrade = calculatePercentageGrade();
    
    // Get contribution weights
    const contributionWeight = parseFloat('{{ $contributionWeight }}');
    const trimesterWeight = parseFloat('{{ $trimesterWeight }}');
    
    // Calculate final contribution
    const finalContribution = (percentageGrade * contributionWeight * trimesterWeight) / 10000;
    
    // Update displays
    updateGradeDisplays(percentageGrade, finalContribution);
}
```

### 3. View Controllers
The controllers provide the necessary data for the views:

```php
class AssessmentController extends Controller
{
    public function create(Subject $subject, Module $module)
    {
        // Get assessment weights for the module
        $assessmentWeights = $module->assessmentStructures()
            ->where('is_trimester_weight', false)
            ->with('contributionType')
            ->get()
            ->mapWithKeys(function ($weight) {
                return [$weight->contributionType->name => $weight->weight];
            });

        return view('admin.assessments.create', compact(
            'subject', 
            'module', 
            'assessmentWeights'
        ));
    }
}

class AssessmentAllocationSubmissionController extends Controller
{
    public function showGradeForm(AssessmentAllocation $allocation, AssessmentAllocationSubmission $submission)
    {
        // Get assessment weight information
        $assessment = $allocation->assessment;
        $contributionWeight = $assessment->getContributionWeight();
        $trimesterWeight = $assessment->getTrimesterWeight($allocation->semester_id);

        return view('admin.submissions.grade', compact(
            'allocation',
            'submission',
            'contributionWeight',
            'trimesterWeight'
        ));
    }
}
```

### 4. View Features
- Real-time grade calculation as marks are entered
- Clear visualization of weight contributions
- Automatic validation of grade limits
- Support for both individual and group submissions
- Handles multiple assessment types:
  * Question-based assessments
  * File upload assessments
  * Group submissions

## Important Notes

1. Assessment types must match the defined contribution types
2. Each module's assessment type weights should total 100%
3. Trimester weights should total 100%
4. Multiple assessments of the same type share that type's weight allocation
5. The system handles both individual and group submissions
6. Supports both question-based and file upload assessments
