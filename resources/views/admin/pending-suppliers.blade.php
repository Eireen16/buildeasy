<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Pending Supplier Approvals
        </h2>
    </x-slot>

    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
            @if(session('success'))
                <div class="mb-4 text-green-600">
                    {{ session('success') }}
                </div>
            @endif

            <h3 class="text-lg font-semibold mb-4">Pending Suppliers</h3>

            @if($suppliers->count() > 0)
                <table class="w-full text-left">
                    <thead>
                        <tr>
                            <th class="px-4 py-2">Username</th>
                            <th class="px-4 py-2">Email</th>
                            <th class="px-4 py-2">Company</th>
                            <th class="px-4 py-2">License</th>
                            <th class="px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($suppliers as $supplier)
                            <tr class="border-t">
                                <td class="px-4 py-2">{{ $supplier->user->username }}</td>
                                <td class="px-4 py-2">{{ $supplier->user->email }}</td>
                                <td class="px-4 py-2">{{ $supplier->company_name }}</td>
                                <td class="px-4 py-2">{{ $supplier->license_number }}</td>
                                <td class="px-4 py-2 flex space-x-2">
                                    <!-- Approve Button -->
                                    <form action="{{ route('admin.approve.supplier', ['id' => $supplier->id]) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-semibold px-4 py-2 rounded shadow">Approve</button>
                                    </form>

                                    <!-- Delete Button -->
                                    <form action="{{ route('admin.delete.supplier', ['id' => $supplier->id]) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-semibold px-4 py-2 rounded shadow">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>No pending suppliers.</p>
            @endif
        </div>
    </div>
</x-app-layout>

