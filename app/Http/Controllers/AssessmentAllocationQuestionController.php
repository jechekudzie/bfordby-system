<?php

namespace App\Http\Controllers;

use App\Models\AssessmentAllocation;
use App\Models\AssessmentAllocationQuestion;
use Illuminate\Http\Request;

class AssessmentAllocationQuestionController extends Controller
{
    public function index(AssessmentAllocation $allocation)
    {
        $questions = $allocation->questions()->paginate(10);
        return view('admin.assessment-allocation-questions.index', compact('allocation', 'questions'));
    }

    public function create(AssessmentAllocation $allocation)
    {
        return view('admin.assessment-allocation-questions.create', compact('allocation'));
    }

    public function store(Request $request, AssessmentAllocation $allocation)
    {
        $validated = $request->validate([
            'question_text' => 'required|string',
            'question_type' => 'required|in:text,multiple_choice',
            'weight' => 'required|integer|min:1',
            'options' => 'required_if:question_type,multiple_choice|array|min:2',
            'options.*.option_text' => 'required_if:question_type,multiple_choice|string',
            'correct_option' => 'required_if:question_type,multiple_choice|integer|min:0',
        ]);

        $question = $allocation->questions()->create([
            'question_text' => $validated['question_text'],
            'question_type' => $validated['question_type'],
            'weight' => $validated['weight'],
        ]);

        if ($validated['question_type'] === 'multiple_choice') {
            foreach ($validated['options'] as $index => $option) {
                $question->options()->create([
                    'option_text' => $option['option_text'],
                    'is_correct' => $index === (int)$validated['correct_option'],
                ]);
            }
        }

        return redirect()
            ->route('admin.assessment-allocation-questions.index', $allocation)
            ->with('success', 'Question created successfully');
    }

    public function show(AssessmentAllocation $allocation, AssessmentAllocationQuestion $question)
    {
        $question->load('options');
        return view('admin.assessment-allocation-questions.show', compact('allocation', 'question'));
    }

    public function edit(AssessmentAllocation $allocation, AssessmentAllocationQuestion $question)
    {
        $question->load('options');
        return view('admin.assessment-allocation-questions.edit', compact('allocation', 'question'));
    }

    public function update(Request $request, AssessmentAllocation $allocation, AssessmentAllocationQuestion $question)
    {
        $validated = $request->validate([
            'question_text' => 'required|string',
            'question_type' => 'required|in:text,multiple_choice',
            'weight' => 'required|integer|min:1',
            'options' => 'required_if:question_type,multiple_choice|array|min:2',
            'options.*.option_text' => 'required_if:question_type,multiple_choice|string',
            'correct_option' => 'required_if:question_type,multiple_choice|integer|min:0',
        ]);

        $question->update([
            'question_text' => $validated['question_text'],
            'question_type' => $validated['question_type'],
            'weight' => $validated['weight'],
        ]);

        if ($validated['question_type'] === 'multiple_choice') {
            // Delete existing options
            $question->options()->delete();
            
            // Create new options
            foreach ($validated['options'] as $index => $optionData) {
                $question->options()->create([
                    'option_text' => $optionData['option_text'],
                    'is_correct' => $index === (int)$validated['correct_option'],
                ]);
            }
        } else {
            // If changing to text type, remove any existing options
            $question->options()->delete();
        }

        return redirect()
            ->route('admin.assessment-allocation-questions.index', $allocation)
            ->with('success', 'Question updated successfully');
    }

    public function destroy(AssessmentAllocation $allocation, AssessmentAllocationQuestion $question)
    {
        $question->options()->delete(); // Delete associated options first
        $question->delete();

        return redirect()
            ->route('admin.assessment-allocation-questions.index', $allocation)
            ->with('success', 'Question deleted successfully');
    }

    public function reorder(Request $request, AssessmentAllocation $allocation)
    {
        $questions = $request->input('questions');
        
        foreach ($questions as $question) {
            AssessmentAllocationQuestion::where('id', $question['id'])
                ->update(['order' => $question['order']]);
        }
        
        return response()->json(['success' => true]);
    }
}
