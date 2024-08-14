<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Workspace;
use App\Models\User;
use App\Models\Transfer;

class TransferController extends Controller
{
    protected $workspace;
    protected $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            // fetch session and use it in entire class with constructor
            $this->workspace = Workspace::find(session()->get('workspace_id'));
            $this->user = getAuthenticatedUser();
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $transfers = Transfer::with(['fromUser', 'toUser'])
            ->where('workspace_id', $this->workspace->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('transfers.index', compact('transfers'));
    }

    public function create()
    {
        $users = User::where('workspace_id', $this->workspace->id)
            ->where('id', '!=', $this->user->id)
            ->get();

        return view('transfers.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'from_user_id' => 'required|exists:users,id',
            'to_user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0',
            'reason' => 'required|string|max:255',
        ]);

        $transfer = Transfer::create([
            'workspace_id' => $this->workspace->id,
            'from_user_id' => $request->from_user_id,
            'to_user_id' => $request->to_user_id,
            'amount' => $request->amount,
            'reason' => $request->reason,
        ]);

        return redirect()->route('transfers.index')
            ->with('success', 'Transfer created successfully.');
    }
}