<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AbstractFlow - Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800 font-sans antialiased">

    <nav class="bg-indigo-700 text-white p-4 flex justify-between items-center shadow-md">
        <div>
            <h1 class="text-xl font-bold tracking-tight">AbstractFlow Admin Console</h1>
            <p class="text-xs text-indigo-200 font-medium">System Administrator Operations Portal</p>
        </div>
        <form action="/web-logout" method="POST">
            @csrf 
            <button type="submit" class="text-sm bg-indigo-800 px-4 py-2 rounded-md font-semibold hover:bg-indigo-900 transition duration-150">
                Logout
            </button>
        </form>
    </nav>

    <div class="p-6 max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="space-y-5 col-span-1">
            
            <div class="bg-white p-5 rounded-lg shadow-sm border border-indigo-100">
                <h2 class="text-sm font-bold mb-3 text-indigo-600 border-b pb-1.5 flex items-center gap-1.5">
                    <span>➕</span> Create New Abstractor Account
                </h2>
                <form action="/web/admin/create-user" method="POST" id="userForm" class="space-y-2.5 text-xs">
                    @csrf
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">Full Name</label>
                        <input type="text" name="name" class="w-full p-2 border rounded bg-gray-50 focus:outline-none focus:ring-1 focus:ring-indigo-500" placeholder="e.g. John Doe" required>
                    </div>
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">Email Address (Login ID)</label>
                        <input type="email" name="email" class="w-full p-2 border rounded bg-gray-50 focus:outline-none focus:ring-1 focus:ring-indigo-500" placeholder="e.g. john@abstractflow.com" required>
                    </div>
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">Temporary Security Password</label>
                        <input type="password" name="password" class="w-full p-2 border rounded bg-gray-50 focus:outline-none focus:ring-1 focus:ring-indigo-500" placeholder="Min 6 characters" required>
                    </div>
                    <button type="submit" class="w-full bg-emerald-600 text-white font-bold p-2 rounded hover:bg-emerald-700 transition mt-1 shadow-sm">
                        Register Abstractor
                    </button>
                </form>
            </div>

            <div class="bg-white p-5 rounded-lg shadow-sm border border-orange-200 bg-orange-50/20">
                <h2 class="text-sm font-bold mb-3 text-orange-700 border-b pb-1.5 flex items-center gap-1.5">
                    <span>🔑</span> Registered Users Credentials Board
                </h2>
                <div class="max-h-48 overflow-y-auto space-y-2 pr-1">
                    @forelse($abstractors as $abs)
                        <div class="bg-white p-2.5 rounded border text-[11px] space-y-0.5 shadow-sm">
                            <div class="flex justify-between font-bold text-gray-800">
                                <span>👤 {{ $abs->name }}</span>
                                <span class="text-[9px] text-gray-400 font-medium">UID: #{{ $abs->id }}</span>
                            </div>
                            <p class="text-gray-600"><span class="font-bold text-gray-500">Login ID:</span> {{ $abs->email }}</p>
                            <p class="text-indigo-600"><span class="font-bold text-gray-500">Password:</span> <span class="bg-gray-100 px-1 rounded font-mono text-[10px]">{{ $abs->raw_password ?? 'Encrypted_Hash' }}</span></p>
                        </div>
                    @empty
                        <p class="text-gray-400 text-center py-4 text-xs">No active abstractor accounts configured.</p>
                    @endforelse
                </div>
            </div>

            <div class="bg-white p-5 rounded-lg shadow-sm border border-gray-200">
                <h2 class="text-sm font-bold mb-3 text-indigo-600 border-b pb-1.5 flex items-center gap-1.5">
                    <span>📥</span> Manual Intake (Excel Reference Rows)
                </h2>
                <form action="/api/admin/tickets/create" method="POST" id="ticketForm" class="space-y-2.5 text-xs">
                    @csrf
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block font-semibold text-gray-700 mb-1">Order ID</label>
                            <input type="text" name="order_id" class="w-full p-2 border rounded focus:outline-none focus:ring-1 focus:ring-indigo-500" placeholder="e.g. ORD-992" required>
                        </div>
                        <div>
                            <label class="block font-semibold text-gray-700 mb-1">Client Name</label>
                            <input type="text" name="client_name" class="w-full p-2 border rounded focus:outline-none focus:ring-1 focus:ring-indigo-500" placeholder="e.g. Chase Bank" required>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block font-semibold text-gray-700 mb-1">Loan / Ref Num</label>
                            <input type="text" name="loan_number" class="w-full p-2 border rounded focus:outline-none focus:ring-1 focus:ring-indigo-500" placeholder="e.g. 62-202-1" required>
                        </div>
                        <div>
                            <label class="block font-semibold text-gray-700 mb-1">Product Type</label>
                            <input type="text" name="product_type" class="w-full p-2 border rounded focus:outline-none focus:ring-1 focus:ring-indigo-500" placeholder="e.g. Two Owner Search" required>
                        </div>
                    </div>
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">Property Address</label>
                        <input type="text" name="property_address" class="w-full p-2 border rounded focus:outline-none focus:ring-1 focus:ring-indigo-500" placeholder="e.g. 123 Main St" required>
                    </div>
                    <div class="grid grid-cols-3 gap-2">
                        <div><label class="block font-semibold mb-1">City</label><input type="text" name="city" class="w-full p-2 border rounded" required></div>
                        <div><label class="block font-semibold mb-1">State</label><input type="text" name="state" class="w-full p-2 border rounded" placeholder="NJ" required></div>
                        <div><label class="block font-semibold mb-1">County</label><input type="text" name="county" class="w-full p-2 border rounded" placeholder="Camden" required></div>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div><label class="block font-semibold mb-1">Parcel ID</label><input type="text" name="parcel_id" class="w-full p-2 border rounded" required></div>
                        <div><label class="block font-semibold mb-1">Due Date</label><input type="datetime-local" name="due_date" class="w-full p-2 border rounded" required></div>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div><label class="block font-semibold mb-1">Borrower</label><input type="text" name="borrower_name" class="w-full p-2 border rounded" required></div>
                        <div><label class="block font-semibold mb-1">Co-Borrower</label><input type="text" name="co_borrower_name" class="w-full p-2 border rounded"></div>
                    </div>
                    <div class="grid grid-cols-3 gap-1 bg-gray-50 p-2 rounded border border-gray-100">
                        <div><label class="block text-[10px] font-bold text-gray-600 mb-0.5">Search ($)</label><input type="number" step="0.01" name="search_rate" class="w-full p-1 border rounded" value="0.00"></div>
                        <div><label class="block text-[10px] font-bold text-gray-600 mb-0.5">Copy ($)</label><input type="number" step="0.01" name="copy_rate" class="w-full p-1 border rounded" value="0.00"></div>
                        <div><label class="block text-[10px] font-bold text-gray-600 mb-0.5">Total ($)</label><input type="number" step="0.01" name="total_cost" class="w-full p-1 border rounded" value="0.00"></div>
                    </div>
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">Assign Primary Abstractor</label>
                        <select name="assigned_user_id" class="w-full p-2 border rounded bg-white">
                            <option value="">-- Choose Abstractor --</option>
                            @foreach($abstractors as $abs)
                                <option value="{{ $abs->id }}">{{ $abs->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="w-full bg-indigo-600 text-white font-bold p-2.5 rounded hover:bg-indigo-700 transition mt-2 shadow-sm">Submit Ticket to System</button>
                </form>
            </div>
        </div>

        <div class="bg-white p-5 rounded-lg shadow-sm border border-gray-200 col-span-1 lg:col-span-2 overflow-x-auto h-fit">
            <h2 class="text-lg font-bold mb-4 border-b pb-2 text-indigo-600 flex items-center gap-1.5">
                <span>📊</span> Active Workflow Management Monitoring Panel
            </h2>
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-gray-100 text-gray-700 uppercase tracking-wider text-[10px] border-b">
                        <th class="p-2.5 border-x">Order ID</th>
                        <th class="p-2.5 border-x">Client & Borrower</th>
                        <th class="p-2.5 border-x">County Space</th>
                        <th class="p-2.5 border-x">Assigned Agent</th>
                        <th class="p-2.5 border-x">Status Node</th>
                        <th class="p-2.5 border-x">Total Cost</th>
                        <th class="p-2.5 border-x">Actions</th>
                    </tr>
                </thead>
              <tbody class="divide-y divide-gray-100">
    @foreach($tickets as $t)
    <tr class="hover:bg-gray-50/70 transition duration-75">
        <td class="p-2.5 font-bold text-indigo-700 whitespace-nowrap">{{ $t->order_id }}</td>
        <td class="p-2.5">
            <span class="block font-semibold text-gray-900">{{ $t->client_name }}</span>
            <span class="block text-gray-500 text-[10px] mt-0.5">Borrower: {{ $t->borrower_name }}</span>
        </td>
        <td class="p-2.5 text-gray-600">{{ $t->county }}, {{ $t->state }}</td>
        <td class="p-2.5 text-gray-700 font-medium">
            @if($t->assignedUser)
                <span class="block text-gray-900 font-semibold">{{ $t->assignedUser->name }}</span>
                <button type="button" onclick="openActivityModal({{ $t->id }}, '{{ $t->order_id }}')" class="block text-[10px] text-indigo-600 font-bold hover:underline mt-1 bg-transparent border-0 p-0 cursor-pointer focus:outline-none">
                    👁️ View User Logs
                </button>
            @else
                <span class="text-red-500 font-semibold">🔴 Unassigned</span>
            @endif
        </td>
        <td class="p-2.5">
            <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wide inline-block
                {{ $t->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                {{ $t->status === 'stalled' ? 'bg-red-100 text-red-800 animate-pulse' : '' }}
                {{ $t->status === 'submitted_qc' ? 'bg-yellow-100 text-yellow-800 animate-pulse' : '' }}
                {{ $t->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : '' }}
                {{ $t->status === 'assigned' ? 'bg-purple-100 text-purple-800' : '' }}
            ">
                {{ $t->status === 'completed' ? 'Completed (Pending PDF)' : $t->status }}
            </span>
        </td>
        <td class="p-2.5 font-bold text-gray-600">${{ number_format($t->total_cost, 2) }}</td>
        <td class="p-2.5 text-center">
            @if($t->status === 'submitted_qc' && $t->pdf_path)
                <a href="/storage/{{ $t->pdf_path }}" target="_blank" class="block text-center text-[10px] bg-emerald-500 text-white rounded px-2 py-1 font-bold hover:bg-emerald-600 transition shadow-sm">
                    Review PDF
                </a>
            @endif

            @if($t->status === 'stalled')
                <form action="/web/admin/tickets/{{ $t->id }}/reassign" method="POST" class="flex flex-col gap-1 items-center bg-gray-50 p-1 rounded border border-red-200 mt-0.5 max-w-[150px] mx-auto">
                    @csrf
                    <select name="assigned_user_id" class="w-full text-[10px] p-1 border rounded bg-white font-medium" required>
                        <option value="">-- Reassign To --</option>
                        @foreach($abstractors as $abs)
                            @if($t->assigned_user_id !== $abs->id)
                                <option value="{{ $abs->id }}">{{ $abs->name }}</option>
                            @endif
                        @endforeach
                    </select>
                    <button type="submit" class="w-full text-[9px] bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-0.5 rounded transition shadow-xs">
                        Confirm Shift
                    </button>
                </form>
            @endif
        </td>
    </tr>
    @endforeach
</tbody>
            </table>
        </div>
    </div>

    <div id="activityModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-xl p-5 max-w-md w-full shadow-2xl border">
            <div class="flex justify-between items-center border-b pb-2.5 mb-3.5">
                <h3 class="font-bold text-gray-900 text-sm flex items-center gap-1">
                    <span>📋</span> Abstractor Timeline: <span id="modalOrderId" class="text-indigo-600 font-extrabold"></span>
                </h3>
                <button type="button" onclick="closeModal()" class="text-gray-400 hover:text-gray-700 font-bold text-xl focus:outline-none">
                    &times;
                </button>
            </div>
            <div id="modalContent" class="space-y-2.5 max-h-72 overflow-y-auto text-xs bg-gray-50 p-3.5 rounded-lg border"></div>
        </div>
    </div>

    <script>
        // Form post handling triggers automatic seamless reload on success
        document.getElementById('ticketForm').addEventListener('submit', function() {
            setTimeout(function() { window.location.reload(); }, 600);
        });
        document.getElementById('userForm').addEventListener('submit', function() {
            setTimeout(function() { window.location.reload(); }, 600);
        });

        // Fixed Javascript Fetch Promises (Removed broken PHP arrow syntax tokens)
        function openActivityModal(ticketId, orderId) {
            document.getElementById('modalOrderId').innerText = orderId;
            const content = document.getElementById('modalContent');
            content.innerHTML = '<p class="text-gray-500 text-center py-4 animate-pulse font-medium">Fetching latest timeline feed notes...</p>';
            document.getElementById('activityModal').classList.remove('hidden');

            fetch('/fetch-ticket-logs/' + ticketId)
                .then(res => {
                    if (!res.ok) throw new Error('HTTP State Exception: ' + res.status);
                    return res.json();
                })
                .then(data => {
                    if(!data || data.length === 0) {
                        content.innerHTML = '<p class="text-gray-400 text-center py-6 font-medium">No milestone entries registered for this profile segment.</p>';
                        return;
                    }
                    content.innerHTML = '';
                    data.forEach(act => {
                        let d = new Date(act.created_at);
                        let dateString = d.toLocaleDateString() + ' ' + d.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
                        content.innerHTML += `
                            <div class="border-b border-gray-200 pb-2 last:border-0 last:pb-0">
                                <span class="text-[10px] text-gray-400 font-semibold block mb-0.5">${dateString}</span>
                                <p class="text-gray-700 font-medium leading-relaxed">${escapeHtml(act.note)}</p>
                            </div>`;
                    });
                })
                .catch(err => {
                    console.error(err);
                    content.innerHTML = '<p class="text-red-500 text-center py-4 font-semibold">⚠️ Failed to load telemetry activity logs.</p>';
                });
        }

        function closeModal() { document.getElementById('activityModal').classList.add('hidden'); }
        function escapeHtml(text) { if (!text) return ''; return text.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;"); }
    </script>
</body>
</html>