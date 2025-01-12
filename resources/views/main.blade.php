<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Template Page</title>
        <script src="https://cdn.tailwindcss.com"></script>

        <style>
            /* Custom styles (if needed) */
        </style>
    </head>
    <body class="bg-cyan-900 text-gray-900 font-sans">

        <main class="container mx-auto max-w-4xl p-4 grid place-items-center">
            <section class="my-8">
                <a
                    href="#"
                    class="group max-w-4xl relative block overflow-hidden mt-2"
                >
                    <div class="relative p-6 rounded-lg text-center">
                        <h1 class="text-gray-100">
                            <span
                                class="px-3 py-1 rounded-full bg-gray-800 font-bold uppercase text-4xl"
                                >Mauritius Tech Meetups</span
                            >
                        </h1>

                        <h3 class="mt-1.5 text-lg font-medium text-gray-100">
                            All the tech meetups from the many local community groups, in one place!
                        </h3>
                    </div>
                </a>
            </section>

            @foreach($meetups as $meetup)
                <section class="mt-8" style="width:inherit">
                    <a href="{{ $meetup->registration }}" class="group relative block overflow-hidden">
                        <div
                            class="relative border-8 border-red-600 bg-white p-6 rounded-lg"
                        >
                            <p class="text-gray-100">
                                <span
                                    class="px-3 py-1 rounded-full bg-cyan-900 font-bold uppercase"
                                    >{{$meetup->type}}</span
                                >

                                <span
                                    class="px-3 py-1 rounded-full bg-gray-800 font-bold uppercase"
                                    >{{$meetup->community}}</span
                                >
                            </p>

                            <h3 class="mt-1.5 text-lg font-medium text-gray-900">
                                {{$meetup->title}}
                            </h3>

                            <p class="mt-1.5 line-clamp-3 text-gray-700">
                                {{$meetup->abstract}}
                                <br>
                            </p>
                            <p>
                            <span
                                class="px-3 py-1 rounded-full bg-cyan-900 font-bold uppercase"
                                >{{$meetup->location}}</span
                            >
                            </p>
                        </div>
                    </a>
                </section>

            @endforeach
        </main>

        <footer class="bg-gray-800 text-white py-4">
            <div class="container mx-auto text-center">
                <p>Made by the local community groups.</p>
            </div>
        </footer>

        <script>
            // Custom JavaScript (if needed)
        </script>
    </body>
</html>
