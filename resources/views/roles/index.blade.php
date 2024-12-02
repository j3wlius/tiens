<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Role Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <!-- Create Role Form -->
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Create New Role</h3>
                    <form method="POST" action="{{ route('roles.store') }}" class="space-y-4">
                        @csrf
                        <div>
                            <x-label for="name" value="{{ __('Role Name') }}" />
                            <x-input id="name" class="block mt-1 w-full" type="text" name="name" required />
                        </div>

                        <div>
                            <x-label value="{{ __('Permissions') }}" />
                            <div class="mt-2 grid grid-cols-1 md:grid-cols-3 gap-4">
                                @foreach($permissions as $permission)
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" 
                                               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <span class="ml-2">{{ $permission->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="flex items-center justify-end">
                            <x-button>
                                {{ __('Create Role') }}
                            </x-button>
                        </div>
                    </form>
                </div>

                <!-- Existing Roles -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Existing Roles</h3>
                    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Permissions</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Users</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($roles as $role)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $role->name }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex flex-wrap gap-2">
                                                @foreach($role->permissions as $permission)
                                                    <span class="px-2 py-1 text-xs font-medium bg-gray-100 rounded-full">
                                                        {{ $permission->name }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $role->users->count() }} users</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            @if($role->name !== 'super-admin')
                                                <button onclick="openEditModal('{{ $role->id }}')" class="text-indigo-600 hover:text-indigo-900 mr-4">Edit</button>
                                                <form action="{{ route('roles.destroy', $role) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this role?')">
                                                        Delete
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-gray-400">System Role</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Role Modal -->
    <div id="editRoleModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 hidden">
        <div class="flex items-center justify-center min-h-screen">
            <div class="bg-white rounded-lg p-8 max-w-2xl w-full mx-4">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Edit Role</h3>
                <form id="editRoleForm" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <x-label for="edit_name" value="{{ __('Role Name') }}" />
                        <x-input id="edit_name" class="block mt-1 w-full" type="text" name="name" required />
                    </div>

                    <div>
                        <x-label value="{{ __('Permissions') }}" />
                        <div class="mt-2 grid grid-cols-1 md:grid-cols-3 gap-4" id="editPermissions">
                            <!-- Permissions will be populated by JavaScript -->
                        </div>
                    </div>

                    <div class="flex items-center justify-end space-x-4">
                        <button type="button" onclick="closeEditModal()" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 active:bg-gray-500 focus:outline-none focus:border-gray-500 focus:ring focus:ring-gray-300 disabled:opacity-25 transition">
                            Cancel
                        </button>
                        <x-button>
                            {{ __('Update Role') }}
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function openEditModal(roleId) {
            const modal = document.getElementById('editRoleModal');
            const form = document.getElementById('editRoleForm');
            const nameInput = document.getElementById('edit_name');
            const permissionsContainer = document.getElementById('editPermissions');

            // Get role data
            fetch(`/roles/${roleId}/edit`)
                .then(response => response.json())
                .then(data => {
                    nameInput.value = data.role.name;
                    form.action = `/roles/${roleId}`;

                    // Populate permissions
                    permissionsContainer.innerHTML = data.permissions.map(permission => `
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="permissions[]" value="${permission.name}"
                                   ${data.role.permissions.some(p => p.id === permission.id) ? 'checked' : ''}
                                   class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <span class="ml-2">${permission.name}</span>
                        </label>
                    `).join('');
                });

            modal.classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editRoleModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('editRoleModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeEditModal();
            }
        });
    </script>
    @endpush
</x-app-layout>
