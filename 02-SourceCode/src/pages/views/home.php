<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-4">Welcome to the Home Page</h1>
    <hr class="mb-6">
    <p class="text-gray-600 mb-8">This is the home page of the application.</p>

    <form action="{{ $usersLink }}" method="post" class="max-w-md space-y-4">
        <div class="space-y-1">
            <input type="text" name="name" placeholder="Name" 
                value="{{ $dataValidation['name']["value"] ?? '' }}"
                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            <span class="text-red-500 text-sm">{{ $dataValidation['name']["error"] ?? '' }}</span>
        </div>

        <div class="space-y-1">
            <input type="email" name="email" placeholder="Email" 
                value="{{ $dataValidation['email']["value"] ?? '' }}"
                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            <span class="text-red-500 text-sm">{{ $dataValidation['email']["error"] ?? '' }}</span>
        </div>

        <div class="space-y-1">
            <input type="date" name="birthdate" placeholder="Birthdate" 
                value="{{ $dataValidation['birthdate']["value"] ?? '' }}"
                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            <span class="text-red-500 text-sm">{{ $dataValidation['birthdate']["error"] ?? '' }}</span>
        </div>

        <div class="space-y-1">
            <select name="element[]" multiple 
                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Select an element</option>
                <option value="fire" {{ in_array("fire", $dataValidation['element']["value"] ?? []) ? 'selected' : '' }}>Fire</option>
                <option value="water" {{ in_array("water", $dataValidation['element']["value"] ?? []) ? 'selected' : '' }}>Water</option>
                <option value="earth" {{ in_array("earth", $dataValidation['element']["value"] ?? []) ? 'selected' : '' }}>Earth</option>
                <option value="air" {{ in_array("air", $dataValidation['element']["value"] ?? []) ? 'selected' : '' }}>Air</option>
            </select>
            <span class="text-red-500 text-sm">{{ $dataValidation['element']["error"] ?? '' }}</span>
        </div>

        <div class="space-y-1">
            <input type="text" name="hobby" placeholder="Hobby" 
                value="{{ $dataValidation['hobby']["value"] ?? '' }}"
                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            <span class="text-red-500 text-sm">{{ $dataValidation['hobby']["error"] ?? '' }}</span>
        </div>

        <button type="submit" 
            class="w-full bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600 transition duration-200">
            Create User
        </button>
    </form>
</div>