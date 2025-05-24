<x-app-layout>
<div class="container">
    <h1>Admin Dashboard</h1>

    <p>Welcome, Admin!</p>

    <div style="margin-top: 20px;">
        <a href="{{ route('admin.pending.suppliers') }}" 
           style="padding: 10px 20px; background-color: #3490dc; color: white; text-decoration: none; border-radius: 5px;">
            View Pending Suppliers
        </a>
    </div>
</div>
</x-app-layout>

