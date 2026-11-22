@extends('layouts.marketplace')

@section('title', 'Community guidelines')

@section('content')

    <div class="bg-white rounded-lg shadow p-6 md:p-8 max-w-3xl">

        <h1 class="text-2xl font-bold text-gray-900 mb-1">Community guidelines</h1>
        <p class="text-sm text-gray-500 mb-6">Please read these before you buy, sell or offer a service.</p>

        <section class="mb-6">
            <h2 class="text-lg font-semibold text-blue-800 mb-2">What you can post</h2>
            <p class="text-gray-700">
                Items and services that you own or may lawfully offer, described honestly, with a fair price,
                the right category and photos that are yours.
            </p>
        </section>

        <section class="mb-6">
            <h2 class="text-lg font-semibold text-blue-800 mb-2">What you must not post</h2>
            <ul class="list-disc ml-6 space-y-1 text-gray-700">
                <li>Illegal, stolen or counterfeit items</li>
                <li>Weapons, drugs or dangerous goods</li>
                <li>Adult, hateful or abusive content</li>
                <li>Misleading descriptions or photos that are not yours</li>
            </ul>
        </section>

        <section class="mb-6">
            <h2 class="text-lg font-semibold text-blue-800 mb-2">Meeting safely</h2>
            <ul class="list-disc ml-6 space-y-1 text-gray-700">
                <li>Meet in a busy public place on campus, in daytime, and tell a friend where you are going.</li>
                <li>Bring a friend for expensive items.</li>
                <li>Inspect the item before you pay, and never send money in advance.</li>
                <li>Be careful if someone insists on meeting somewhere private or off campus.</li>
                <li>Do not share passwords, ID numbers or other sensitive information.</li>
            </ul>
        </section>

        <section class="mb-6">
            <h2 class="text-lg font-semibold text-blue-800 mb-2">About payments</h2>
            <p class="text-gray-700">
                This system does not handle payments or deliveries. Buyers and sellers agree on payment themselves
                and meet in person. The system does not guarantee the quality or authenticity of any item.
            </p>
        </section>

        <section class="mb-6">
            <h2 class="text-lg font-semibold text-blue-800 mb-2">Reported listings</h2>
            <p class="text-gray-700">
                When a listing is reported, an administrator reviews it. While the review is open, the seller cannot
                edit or delete that listing, so the evidence is kept. You can follow the outcome under
                <strong>My reports</strong>.
            </p>
        </section>

        <section class="mb-6 rounded border-l-4 border-red-600 bg-red-50 p-4">
            <h2 class="text-lg font-semibold text-red-800 mb-2">If something goes wrong</h2>
            <ol class="list-decimal ml-6 space-y-1 text-gray-700">
                <li>
                    <strong>Your safety comes first.</strong> Move to a safe, busy place. If you were robbed,
                    threatened or hurt, contact campus security or the police.
                </li>
                <li>Keep any evidence: messages, photos, the listing, and the other person's name and registration number.</li>
                <li>
                    Report it using <strong>Report this listing</strong> and choose <strong>Safety incident</strong>.
                    Administrators review safety reports first.
                </li>
                <li>
                    The administrator can suspend the account straight away and, when lawfully requested,
                    help MUBS administration or the police.
                </li>
            </ol>
        </section>

        <section>
            <h2 class="text-lg font-semibold text-blue-800 mb-2">Your information</h2>
            <p class="text-gray-700">
                We collect only what is needed to run the marketplace. Your phone number and email are not shown on
                listings or profiles.
                @if (Route::has('policy.show') && Route::has('terms.show'))
                    Read our
                    <a href="{{ route('terms.show') }}" class="text-blue-700 underline">Terms and Conditions</a> and
                    <a href="{{ route('policy.show') }}" class="text-blue-700 underline">Privacy Policy</a>.
                @endif
            </p>

            <section class="mb-6">
    <h2 class="text-lg font-semibold text-blue-800 mb-2">Contact us</h2>
    <p class="text-gray-700">
        Questions, problems, or something not covered here?
        Email us at
        <a href="mailto:mubsmarketplace@gmail.com" class="text-blue-700 underline font-medium">
            mubsmarketplace@gmail.com
        </a>
        and we’ll get back to you as soon as possible.
    </p>
</section>
        </section>
    </div>

@endsection