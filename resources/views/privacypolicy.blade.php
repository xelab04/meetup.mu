@extends('layouts.main')

@section('content')
<section class="max-w-4xl mx-auto px-5 md:px-10 pt-10 md:pt-14 pb-16">
    @if (session('message'))
        <div class="mb-6 rounded-xl border border-island-primary/40 bg-island-primary/5 px-4 py-3 text-island-primary text-sm text-center font-medium">
            {{ session('message') }}
        </div>
    @endif
    <div class="bg-island-card border border-island-rule rounded-2xl p-6 md:p-10">
        <h1 class="text-3xl md:text-4xl font-semibold tracking-tight text-island-fg mb-4">
            Privacy Policy and EULA
        </h1>
        <div class="prose dark:prose-invert max-w-none text-island-fg mb-6">
                This should give you an idea of what I will do with your data. I am not a lawyer, and I am too poor to get a legal team. This is obviously not legal advice and users should consult with an attorney for their specific needs though, it is my opinion that if you need to consult with an attorney, you're better off not using meetup.mu.'
            </div>

            <!-- -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Preface</h2>
                <div class="max-w-none text-gray-600 dark:text-gray-300 mb-3">
                    Ahead of you reading what I keep on you and what I track, I should clarify the purpose of meetup.mu. This project is a community project - it is developed by numerous contributors. I (<a href="https://alexbissessur.dev" class="underling !text-blue-600">Alex</a>) am the "main" developer. Meetup.mu is intended as a free (both in freedom and cost) alternative to other platforms, built for tech community groups in Mauritius.
                    Because I believe long policies with legalese and dozens of pages are useless, here's the TLDR: I do not want your data for personal gain.
                </div>

                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Your Data</h2>
                <div class="max-w-none text-gray-600 dark:text-gray-300 mb-3">
                    <ul class="list-disc list-inside">
                        <li>Meetup.mu is a community project and entirely open source.</li>
                        <li>If you use this website without creating an account here, I store no data on you beyond the session details (agent, IP, last activity).</li>
                        <li>When creating an account, you provide me with your name, email address, and a password. As expected, the password is hashed with Bcrypt.</li>
                        <li>The only plaintext data is therefore your name and email address. Email address is needed to prevent spam and so you can get a password reset. Maybe some communitites want to send reminder emails too.</li>
                    </ul>
                </div>

                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Where Your Data is Stored</h2>
                <div class="max-w-none text-gray-600 dark:text-gray-300 mb-3">
                    <ul class="list-disc list-inside">
                        <li>Currently, all components of Meetup.mu are found on my homelab Kubernetes cluster. This includes the scraper, Laravel application, and database.</li>
                        <li>The database is obviously inaccessible to the outside world, and can only be reached from within the Kubernetes cluster (not even from my LAN)</li>
                        <li>Your data resides in Longhorn, a block storage solution for Kubernetes. It offers encryption at rest. However, I have not enabled this feature*</li>
                        <li>All data is backed up to Wasabi S3 on a daily basis. Please read their docs on security <a class="!text-blue-600 underline" href="https://docs.wasabi.com/v1/docs/how-secure-is-my-data-1">here</a>. I'm the only one with the credentials for Wasabi.</li>
                    </ul>
                    * This is because this only helps if someone steals the physical disk/node. And if you have that then you can access etcd and decrypt the volume anyway.
                </div>

                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Who Can See Your Data</h2>
                <div class="prose dark:prose-invert max-w-none text-gray-600 dark:text-gray-300 mb-3">
                    <ul class="list-disc list-inside">
                        <li>I alone, have access to the entire database. This means I can see your name and email address, as well as the session details.</li>
                        <li>Your name and email address are visible to the admins of the community groups when you RSVP to their meetups. I cannot control how community admins use your data.</li>
                        <li>As the data is more specifically in Longhorn, on Kubernetes, your data is probably also safe from Mauritius government agents. /j</li>
                    </ul>
                </div>

                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Terms of Use</h2>
                <div class="prose dark:prose-invert max-w-none text-gray-600 dark:text-gray-300 mb-3">
                    <ul class="list-disc list-inside">
                        <li>I do not particularly care about who uses this website.</li>
                        <li>Having a community group present on this platform does not mean that I (or any other contributor) endorses them.</li>
                        <li>I am the only person who can (and will) add or remove community groups on this platform.</li>
                        <li>I may choose to remove community groups at my own discretion (and within my reasons).</li>
                    </ul>
                </div>

                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Data Regulations</h2>
                <div class="prose dark:prose-invert max-w-none text-gray-600 dark:text-gray-300 mb-3">
                    <ul class="list-disc list-inside">
                        <li>You have probably heard of the GDPR, CCPA, and our local Data Protection Act.</li>
                        <li>I have done my best to respect the above-mentioned regulations.</li>
                        <li>I have not read through the entirety of these regulations, but have applied the best practices summarised throughout.</li>
                    </ul>
                </div>
    </div>
</section>
@endsection
