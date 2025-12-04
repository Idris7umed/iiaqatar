@extends('layouts.app')

@section('title', 'Contact Us')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-4xl font-bold mb-8">Contact Us</h1>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Contact Form -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-bold mb-6">Send Us a Message</h2>
            <form>
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Name</label>
                    <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Your Name">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Email</label>
                    <input type="email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="your@email.com">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Subject</label>
                    <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Message Subject">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Message</label>
                    <textarea rows="5" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Your message..."></textarea>
                </div>
                <button type="submit" class="w-full bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700">
                    Send Message
                </button>
            </form>
        </div>

        <!-- Contact Information -->
        <div>
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-2xl font-bold mb-6">Get In Touch</h2>
                <div class="space-y-4">
                    <div class="flex items-start">
                        <span class="text-blue-600 text-xl mr-4">📧</span>
                        <div>
                            <h3 class="font-semibold">Email</h3>
                            <p class="text-gray-600">info@iiaqatar.org</p>
                            <p class="text-gray-600">support@iiaqatar.org</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <span class="text-blue-600 text-xl mr-4">📞</span>
                        <div>
                            <h3 class="font-semibold">Phone</h3>
                            <p class="text-gray-600">+974 XXXX XXXX</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <span class="text-blue-600 text-xl mr-4">📍</span>
                        <div>
                            <h3 class="font-semibold">Address</h3>
                            <p class="text-gray-600">Doha, Qatar</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <span class="text-blue-600 text-xl mr-4">⏰</span>
                        <div>
                            <h3 class="font-semibold">Business Hours</h3>
                            <p class="text-gray-600">Sunday - Thursday: 8:00 AM - 5:00 PM</p>
                            <p class="text-gray-600">Friday - Saturday: Closed</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-blue-50 rounded-lg p-6">
                <h3 class="font-bold mb-2">Need Immediate Help?</h3>
                <p class="text-gray-700 mb-4">Check out our FAQ section or reach out via our social media channels for quick responses.</p>
                <div class="flex gap-3">
                    <a href="#" class="text-blue-600 hover:text-blue-800">Facebook</a>
                    <a href="#" class="text-blue-600 hover:text-blue-800">Twitter</a>
                    <a href="#" class="text-blue-600 hover:text-blue-800">LinkedIn</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
