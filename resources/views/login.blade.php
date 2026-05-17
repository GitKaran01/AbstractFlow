<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>AbstractFlow - Unified Sign In</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-8 rounded-lg shadow-lg w-96 border border-gray-200">
        <h2 class="text-3xl font-extrabold mb-2 text-center text-indigo-600 tracking-tight">AbstractFlow</h2>
        <p class="text-xs text-gray-500 text-center mb-6 font-medium">Unified Portal Management System</p>
        
        @if($errors->any())
            <div class="bg-red-100 text-red-700 p-2 rounded mb-4 text-xs font-semibold text-center">{{ $errors->first() }}</div>
        @endif

        <form action="/web-login" method="POST">
            @csrf
            
            <div class="mb-4">
                <label class="block text-gray-700 text-xs font-bold uppercase tracking-wider mb-2">Portal Access Mode</label>
                <select name="login_role" class="w-full p-2.5 border rounded-md bg-gray-50 font-medium text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="abstractor">👤 Sign In as Abstractor (User)</option>
                    <option value="admin">🔑 Sign In as System Admin</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-xs font-bold uppercase tracking-wider mb-2">Email Identity</label>
                <input type="email" name="email" class="w-full p-2.5 border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="name@abstractflow.com" required>
            </div>
            
            <div class="mb-6">
                <label class="block text-gray-700 text-xs font-bold uppercase tracking-wider mb-2">Security Password</label>
                <input type="password" name="password" class="w-full p-2.5 border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="••••••••" required>
            </div>
            
            <button type="submit" class="w-full bg-indigo-600 text-white p-2.5 rounded-md font-bold text-sm tracking-wide hover:bg-indigo-700 transition duration-200 shadow-md">Authenticate & Enter</button>
        </form>
    </div>
</body>
</html>