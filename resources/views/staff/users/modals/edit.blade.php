<v-modal name="{{ $modalName }}" height="auto">
    <template v-slot="{ data }">
        <div class="p-6">
            <h2 class="text-lg font-bold mb-8">Update User</h2>
            <form :action="route('staff.users.update', data('user', ''))" method="POST">
                @csrf_token @method('PATCH')
                <div class="mb-2">
                    <label for="name" class="w-full form-label mb-1">
                        Full Name <span class="h-current text-red-500 text-lg">*</span>
                    </label>
                    <input id="name" type="text" name="name" class="w-full form-input" :value="data('user.name')">
                </div>
                <div class="mb-2">
                    <label for="email" class="w-full form-label mb-1">
                        Email <span class="h-current text-red-500 text-lg">*</span>
                    </label>
                    <input id="email" type="email" name="email" class="w-full form-input" :value="data('user.email')">
                </div>
                <div class="mb-2">
                    <label for="roles" class="w-full form-label mb-1">
                        Roles <span class="h-current text-red-500 text-lg">*</span>
                    </label>
                    <select id="roles" name="roles[]" class="w-full form-multiselect" :value="data('user.roles', [])" multiple>
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}"
                                :selected="data('user.roles', []).includes({{ $role->id }})">
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-2">
                    <label for="type" class="w-full form-label">Category<span
                            class="h-current text-red-500 text-lg">*</span></label>
                    <select name="type" id="type" class="w-full form-select" required>
                        <option value="" selected disabled>Select a type for the user</option>
                        @foreach ($types as $type)
                        <option value="{{ $type }}"
                            :selected="data('user.type') === '{{ $type }}'">
                            {{ $type }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="mt-5">
                    <button type="submit" class="btn btn-magenta">Update</button>
                </div>
            </form>
        </div>
    </template>
</v-modal>
