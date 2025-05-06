<?php

namespace App\Http\Controllers;

use App\Models\Proposition;
use App\Models\Project;
use Illuminate\Http\Request;

class PropositionController extends Controller
{
    public function index($project_id)
    {
        $project = Project::with('propositions')->findOrFail($project_id);
        return view('proposition.proposition', ['project' => $project]);
    }

    public function store(Request $request, $project_id)
    {
        $project = Project::findOrFail($project_id);

        $validated = $request->validate([
            'contenu' => 'required|string',
            'budget' => 'required|numeric',
            'date_creation' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_creation',
        ]);

        $validated['author_id'] = session('user_id');

        $project->propositions()->create($validated);

        return redirect()->route('propositions.index', ['project_id' => $project_id])->with('success', 'Proposition created successfully.');
    }

    public function update(Request $request, Proposition $proposition)
    {
        $validated = $request->validate([
            'contenu' => 'required|string',
            'budget' => 'required|numeric',
            'date_creation' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_creation',
            'project_id'=>'required'
        ]);

        $proposition->update($validated);

        return redirect()->route('propositions.index', ['project_id' => $validated['project_id']])->with('success', 'Proposition created successfully.');
    }

    public function destroy(Proposition $proposition)
    {
        $proposition->delete();

        return redirect()->route('propositions.index')->with('success', 'Proposition deleted successfully.');
    }
}