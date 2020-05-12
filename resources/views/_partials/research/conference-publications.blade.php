<div class="flex items-start space-x-6">
    <div class="w-64 pr-4 relative -ml-8 my-2">
        <h3 class="relative pl-8 pr-4 py-2 font-bold bg-magenta-700 text-white shadow">
            Conference Publications
        </h3>
        <svg class="absolute left-0 w-2 text-magenta-900" viewBox="0 0 10 10">
            <path fill="currentColor" d="M0 0 L10 0 L10 10 L0 0"></path>
        </svg>
        @if(auth()->guard('scholars')->check() && auth()->guard('scholars')->id() === $scholar->id)
        <div class="mt-3 text-right">
            <a href="{{ route('publications.conference.create') }}"
                class="btn btn-magenta">
                    New
            </a>
        </div>
        @endif
    </div>
    <ul class="flex-1 border rounded-lg overflow-hidden mb-4">
            @forelse ($conferences as $conference)
            <div class="flex">
                <ul class="flex">
                    @forelse($conference->coAuthors as $coAuthor)
                    <li class="flex items-baseline mt-2">
                        <div class="inline-flex items-center p-2 rounded border hover:bg-gray-300 mx-2">
                            @can('view', $coAuthor)
                            <a href="{{ route('publications.co_authors.show', $coAuthor) }}" target="__blank" class="inline-flex items-center mr-1">
                                <feather-icon name="paperclip" class="h-4 mr-2" stroke-width="2">NOC</feather-icon>
                                <span>{{ $coAuthor->name }}</span>
                            </a>
                            @endcan
                        </div>
                    </li>
                    @empty
                        <p class="ml-2 p-2">No Co-authors associated with this publication.</p>
                    @endforelse
                </ul>
                <div class="ml-auto p-2 flex">
                    @can('update', $conference)
                    <a href="{{ route('publications.conference.edit', $conference) }}"
                        class="p-1 text-blue-600 hover:bg-gray-200 rounded mr-3" title="Edit">
                        <feather-icon name="edit-3" stroke-width="2.5" class="h-current">Edit</feather-icon>
                    </a>
                    @endcan
                    @can('delete', $conference)
                    <form method="POST" action="{{ route('publications.conference.destroy', $conference->id) }}"
                        onsubmit="return confirm('Do you really want to delete this conference?');">
                        @csrf_token
                        @method('DELETE')
                        <button type="submit" class="p-1 hover:bg-gray-200 text-red-700 rounded">
                            <feather-icon name="trash-2" stroke-width="2.5" class="h-current">Delete</feather-icon>
                        </button>
                    </form>
                    @endcan
                </div>
            </div>
            <li class="border-b last:border-b-0 py-3">
                <div class="flex items-baseline">
                    <p class="ml-2 p-2">
                        {{auth()->user()->name . ',' . implode(',', $conference->coAuthors->map->name->toArray())}}
                        {{ $conference->date->format('F Y') }}.
                        <span class="italic"> {{ $conference->paper_title }} </span>
                        {{ $conference->name }},
                        Edition {{ $conference->volume }},
                        pp: {{ $conference->page_numbers[0] }}-{{ $conference->page_numbers[1] }}
                    </p>
                </div>
                <div class="w-full px-4">
                    <details class="ml-2 mt-4 bg-gray-100 border rounded-t cursor-pointer outline-none">
                        <summary class="underline p-2 bg-gray-200 outline-none">Expand</summary>
                        <div class="flex m-2">
                            <div class="w-30 flex">
                                <feather-icon name="users" class="h-current text-blue-600"></feather-icon>
                                <h4 class="ml-1 font-semibold"> Author: </h4>
                            </div>
                            <p class="ml-2"> {{ auth()->user()->name }} </p>
                        </div>
                        <div class="flex m-2">
                            <div class="w-30 flex">
                                <feather-icon name="users" class="h-current text-blue-600"></feather-icon>
                                <h4 class="ml-1 font-semibold"> Co-Authors: </h4>
                            </div>
                            <p class="ml-2"> {{implode(',', $conference->coAuthors->map->name->toArray())}} </p>
                        </div>
                        <div class="m-2 flex">
                            <div class="w-30 flex">
                                <feather-icon name="book-open" class="h-current text-blue-600"></feather-icon>
                                <h4 class="ml-1 font-semibold"> Title: </h4>
                            </div>
                            <p class="ml-2 italic"> {{ $conference->paper_title }} </p>
                        </div>
                        <div class="flex -m-1">
                            <div class="w-3/5">
                                <div class="flex m-2">
                                    <h4 class="font-semibold"> Name: </h4>
                                    <p class="ml-2"> {{ $conference->name }} </p>
                                </div>
                                <div class="flex m-2">
                                    <h4 class="font-semibold"> Address: </h4>
                                    <p class="ml-2"> {{ $conference->city}}, {{ $conference->country }} </p>
                                </div>
                                <div class="flex m-2">
                                    <h4 class="font-semibold"> Indexed In: </h4>
                                    <p class="ml-2"> {{ implode(', ', $conference->indexed_in) }} </p>
                                </div>
                            </div>
                            <div class="w-2/5">
                                <div class="flex m-2">
                                    <h4 class="font-semibold"> Date: </h4>
                                    <p class="ml-2"> {{ $conference->date->format('F Y') }} </p>
                                </div>
                                <div class="flex m-2">
                                    <h4 class="font-semibold">Edition: </h4>
                                    <p class="ml-2"> {{ $conference->volume }} </p>
                                </div>
                                <div class="flex m-2">
                                    <h4 class="font-semibold"> Pages: </h4>
                                    <p class="ml-2"> {{ $conference->page_numbers[0] }}-{{ $conference->page_numbers[1] }} </p>
                                </div>
                            </div>
                        </div>
                    </details>
                </div>
            </li>
        @empty
            <p class="px-4 py-3 text-center text-gray-700 font-bold">No Conferences</p>
        @endforelse
    </ul>
</div>
