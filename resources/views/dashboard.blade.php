<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AbstractFlow - Abstractor Workspace</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800 font-sans antialiased">

    <nav class="bg-indigo-700 text-white p-4 flex justify-between items-center shadow">
        <div>
            <h1 class="text-xl font-bold">AbstractFlow Abstractor Console</h1>
            <p class="text-xs text-indigo-200">Logged in as: <span class="font-bold">{{ Auth::user()->name }}</span></p>
        </div>
        <form action="/web-logout" method="POST">@csrf <button class="text-sm bg-indigo-800 px-3 py-1.5 rounded hover:bg-indigo-900 font-semibold">Logout</button></form>
    </nav>

    <div class="p-6 max-w-7xl mx-auto space-y-6">
        <h2 class="text-lg font-bold text-indigo-600 border-b pb-1">📂 My Assigned Property Workload Orders</h2>
        
        @if($myTickets->isEmpty())
            <div class="bg-white p-8 rounded-lg shadow text-center text-gray-500 font-medium border">
                No tickets are currently assigned to your coverage area queue.
            </div>
        @else
            @foreach($myTickets as $t)
            <div class="bg-white p-5 rounded-lg shadow border border-gray-200 grid grid-cols-1 lg:grid-cols-3 gap-6 mb-5">
                
                <div class="space-y-2 text-xs lg:col-span-1">
                    <div class="flex items-center gap-2">
                        <span class="text-sm font-bold text-indigo-700">{{ $t->order_id }}</span>
                        <span class="bg-gray-100 text-gray-600 font-bold px-1.5 py-0.5 rounded text-[10px] uppercase">{{ $t->product_type }}</span>
                    </div>
                    <p class="text-gray-700"><span class="font-bold">Client:</span> {{ $t->client_name }} | <span class="font-bold">Loan #:</span> {{ $t->loan_number }}</p>
                    <p class="text-gray-600"><span class="font-bold">Address:</span> {{ $t->property_address }}, {{ $t->city }}, {{ $t->state }} ({{ $t->county }} County)</p>
                    <p class="text-gray-600"><span class="font-bold">Borrower:</span> {{ $t->borrower_name }}</p>
                    <p class="text-red-600 font-semibold">⏳ <span class="font-bold">Due Date:</span> {{ date('M d, Y h:i A', strtotime($t->due_date)) }}</p>
                    
                    <div class="pt-2">
                        <span class="font-bold block mb-1">Current State Node:</span>
                        <span class="uppercase px-2 py-1 rounded text-[10px] font-extrabold tracking-wider
                            {{ $t->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $t->status === 'stalled' ? 'bg-red-100 text-red-800 animate-pulse' : '' }}
                            {{ $t->status === 'submitted_qc' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $t->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $t->status === 'assigned' ? 'bg-purple-100 text-purple-800' : '' }}
                        ">{{ $t->status === 'completed' ? 'Completed (Pending PDF)' : $t->status }}</span>
                    </div>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg border text-xs space-y-3 lg:col-span-1">
                    <h3 class="font-bold text-gray-700 border-b pb-1">⚡ Workflow Controls</h3>
                    
                    @if($t->status === 'stalled' || $t->status === 'submitted_qc')
                        <div class="bg-gray-200 text-gray-600 p-4 rounded text-center font-extrabold uppercase tracking-wider border">
                            🔒 Access Frozen / Work Closed
                        </div>
                    @else
                        @if($t->status === 'assigned')
                        <form action="/web/tasks/{{ $t->id }}/accept" method="POST">
                            @csrf
                            <button type="submit" class="w-full bg-blue-600 text-white font-bold p-2.5 rounded hover:bg-blue-700 shadow-sm transition">
                                🚀 Accept & Mark In-Progress
                            </button>
                        </form>
                        @endif

                        @if($t->status === 'in_progress' || $t->status === 'completed')
                        
                            <form action="/web/tasks/{{ $t->id }}/update-status" method="POST" class="bg-white p-2 rounded border space-y-1.5">
                                @csrf
                                <label class="block font-bold text-gray-600 text-[10px] uppercase">File Status State</label>
                                <div class="flex gap-1.5">
                                    <select name="status" class="w-full p-1 border rounded bg-gray-50 text-xs">
                                        <option value="in_progress" {{ $t->status === 'in_progress' ? 'selected' : '' }}>🚀 In Progress</option>
                                        <option value="completed" {{ $t->status === 'completed' ? 'selected' : '' }}>✅ Completed</option>
                                    </select>
                                    <button type="submit" class="bg-gray-800 text-white font-bold px-2.5 rounded text-[11px] hover:bg-black">Set</button>
                                </div>
                            </form>

                            <form action="/web/tasks/{{ $t->id }}/submit" method="POST" enctype="multipart/form-data" class="space-y-2 border-t pt-2 mt-1">
                                @csrf
                                <div>
                                    <label class="block font-bold text-gray-700 mb-0.5">Search Certification Date</label>
                                    <input type="date" name="search_date" class="w-full p-1.5 border rounded" required>
                                </div>
                                <div>
                                    <label class="block font-bold text-gray-700 mb-0.5">Report Package (Max 15MB PDF)</label>
                                    <input type="file" name="final_report" accept="application/pdf" class="w-full text-[11px]" required>
                                </div>
                                <button type="submit" class="w-full bg-emerald-600 text-white font-bold p-2 rounded hover:bg-emerald-700 transition shadow-sm">
                                    📤 Dispatch File Package to QC Review
                                </button>
                            </form>
                        @endif

                        <form action="/web/tasks/{{ $t->id }}/halt" method="POST" class="border-t pt-2 mt-2 space-y-1">
                            @csrf
                            <input type="text" name="note" class="w-full p-1.5 border rounded bg-white" placeholder="Reason for dropping assignment row..." required>
                            <button type="submit" class="w-full bg-red-600 text-white font-bold p-1.5 rounded hover:bg-red-700 transition text-[11px]">
                                🚨 Halt Work / Relinquish Target Queue
                            </button>
                        </form>
                    @endif
                </div>

                <div class="bg-white p-4 rounded-lg border text-xs space-y-3 lg:col-span-1 flex flex-col justify-between">
                    <div>
                        <h3 class="font-bold text-indigo-600 border-b pb-1 mb-2">📋 Live Timeline Feed Notes</h3>
                        <div class="space-y-2 max-h-40 overflow-y-auto pr-1 bg-gray-50 p-2 rounded border">
                            @forelse($t->activities as $act)
                                <div class="border-b border-gray-200/60 pb-1.5 last:border-0 last:pb-0">
                                    <span class="text-[9px] block text-gray-400 font-bold tracking-tight">{{ date('M d, g:i A', strtotime($act->created_at)) }}</span>
                                    <p class="text-gray-700 text-[11px] font-medium leading-relaxed">{{ $act->note }}</p>
                                </div>
                            @empty
                                <p class="text-gray-400 text-center py-6 font-medium">No milestone logs updated for this property array segment.</p>
                            @endforelse
                        </div>
                    </div>
                    
                    @if($t->status !== 'stalled' && $t->status !== 'submitted_qc')
                    <form action="/web/tasks/{{ $t->id }}/log-activity" method="POST" class="pt-2 border-t mt-2 flex gap-1">
                        @csrf
                        <input type="text" name="note" class="w-full p-1.5 border rounded focus:ring-1 focus:ring-indigo-500 text-xs" placeholder="Log dynamic notes update..." required>
                        <button type="submit" class="bg-gray-800 text-white font-bold px-3.5 rounded hover:bg-black transition">Post</button>
                    </form>
                    @endif
                </div>

            </div>
            @endforeach
        @endif
    </div>

</body>
</html>