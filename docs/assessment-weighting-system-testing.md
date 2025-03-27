# Assessment Weighting System Testing Guide

## Unit Tests

### 1. Assessment Weight Tests
```php
class AssessmentWeightTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_calculates_contribution_weight_correctly()
    {
        // Create a module with assessment weights
        $module = Module::factory()->create();
        $courseworkType = AssessmentContributionType::create(['name' => 'Coursework']);
        
        $module->assessmentStructures()->create([
            'assessment_contribution_type_id' => $courseworkType->id,
            'weight' => 25,
            'is_trimester_weight' => false
        ]);

        // Create an assessment
        $assessment = Assessment::factory()->create([
            'module_id' => $module->id,
            'type' => 'Coursework'
        ]);

        $this->assertEquals(25, $assessment->getContributionWeight());
    }

    /** @test */
    public function it_calculates_trimester_weight_correctly()
    {
        // Create module with trimester weights
        $module = Module::factory()->create();
        $courseworkType = AssessmentContributionType::create(['name' => 'Coursework']);
        
        $module->assessmentStructures()->create([
            'assessment_contribution_type_id' => $courseworkType->id,
            'weight' => 30,
            'is_trimester_weight' => true,
            'trimester' => 1
        ]);

        // Create semester and assessment
        $semester = Semester::factory()->create(['trimester' => 1]);
        $assessment = Assessment::factory()->create(['module_id' => $module->id]);

        $this->assertEquals(30, $assessment->getTrimesterWeight($semester->id));
    }
}
```

### 2. Grade Calculation Tests
```php
class GradeCalculationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_calculates_weighted_grade_correctly()
    {
        // Setup module with weights
        $module = Module::factory()->create();
        $courseworkType = AssessmentContributionType::create(['name' => 'Coursework']);
        
        // 25% assessment type weight
        $module->assessmentStructures()->create([
            'assessment_contribution_type_id' => $courseworkType->id,
            'weight' => 25,
            'is_trimester_weight' => false
        ]);
        
        // 30% trimester weight
        $module->assessmentStructures()->create([
            'assessment_contribution_type_id' => $courseworkType->id,
            'weight' => 30,
            'is_trimester_weight' => true,
            'trimester' => 1
        ]);

        // Create assessment with 80% grade
        $semester = Semester::factory()->create(['trimester' => 1]);
        $assessment = Assessment::factory()->create([
            'module_id' => $module->id,
            'type' => 'Coursework'
        ]);
        
        $student = Student::factory()->create();
        $allocation = AssessmentAllocation::factory()->create([
            'assessment_id' => $assessment->id,
            'semester_id' => $semester->id
        ]);
        
        AssessmentAllocationSubmission::factory()->create([
            'assessment_allocation_id' => $allocation->id,
            'student_id' => $student->id,
            'grade' => 80
        ]);

        // 80% × 25% × 30% = 6%
        $this->assertEquals(6, $assessment->calculateWeightedGrade($student->id, $semester->id));
    }

    /** @test */
    public function it_calculates_trimester_grade_correctly()
    {
        // Similar setup as above
        // Test that multiple assessments in a trimester are calculated correctly
        // Should sum up all weighted grades for the trimester
    }

    /** @test */
    public function it_calculates_final_grade_correctly()
    {
        // Test that grades across all trimesters are calculated correctly
        // Should sum up all trimester grades
    }
}
```

### 3. Validation Tests
```php
class AssessmentValidationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function assessment_type_weights_must_total_100_percent()
    {
        $module = Module::factory()->create();
        
        // Create assessment types totaling more than 100%
        $courseworkType = AssessmentContributionType::create(['name' => 'Coursework']);
        $practicalType = AssessmentContributionType::create(['name' => 'Practical']);
        
        $module->assessmentStructures()->createMany([
            [
                'assessment_contribution_type_id' => $courseworkType->id,
                'weight' => 60,
                'is_trimester_weight' => false
            ],
            [
                'assessment_contribution_type_id' => $practicalType->id,
                'weight' => 50,
                'is_trimester_weight' => false
            ]
        ]);

        $this->assertFalse($module->validateAssessmentTypeWeights());
    }

    /** @test */
    public function trimester_weights_must_total_100_percent()
    {
        // Similar test for trimester weights
    }
}
```

## Feature Tests

### 1. Assessment Creation
```php
class AssessmentCreationTest extends TestCase
{
    /** @test */
    public function it_shows_assessment_types_with_weights()
    {
        // Test that create form shows correct assessment types and weights
        $response = $this->get(route('admin.assessments.create', [$subject, $module]));
        
        $response->assertSee('Coursework (25%)');
        $response->assertSee('Practical (15%)');
    }

    /** @test */
    public function it_creates_assessment_with_correct_type()
    {
        // Test assessment creation with type selection
    }
}
```

### 2. Grading Interface
```php
class GradingInterfaceTest extends TestCase
{
    /** @test */
    public function it_shows_grade_contribution_information()
    {
        // Test that grading form shows:
        // - Assessment type weight
        // - Trimester weight
        // - Contribution calculation
    }

    /** @test */
    public function it_updates_contribution_calculation_when_grade_changes()
    {
        // Test JavaScript grade calculation updates
    }
}
```

## Manual Testing Checklist

1. Assessment Type Setup
   - [ ] Create module with different assessment types
   - [ ] Verify weights total 100%
   - [ ] Verify trimester weights total 100%

2. Assessment Creation
   - [ ] Verify correct types shown in dropdown
   - [ ] Verify weights shown correctly
   - [ ] Create assessment of each type

3. Grading Interface
   - [ ] Check grade summary card shows correct weights
   - [ ] Verify real-time calculation updates
   - [ ] Test with different grade values
   - [ ] Verify final contribution calculation

4. Multiple Assessments
   - [ ] Create multiple assessments of same type
   - [ ] Verify they share the type's weight allocation
   - [ ] Check trimester grade calculations

5. Group Submissions
   - [ ] Test grade assignment for group
   - [ ] Verify all members receive same grade
   - [ ] Check weight calculations for group submissions

6. Edge Cases
   - [ ] Test with 0% grades
   - [ ] Test with 100% grades
   - [ ] Test with missing submissions
   - [ ] Test with deleted assessments
