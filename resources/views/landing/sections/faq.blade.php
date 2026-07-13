<section class="py-20 bg-gray-50" id="faq">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900">Frequently Asked Questions</h2>
            <p class="mt-4 text-xl text-gray-600">Find quick answers to common questions.</p>
        </div>
        <div class="mt-12 space-y-4" x-data="{ open: null }">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <button @click="open = open === 1 ? null : 1" class="w-full flex justify-between items-center p-6 text-left font-semibold text-gray-900 hover:text-indigo-600 transition">
                    <span>What is SaaS Builder?</span>
                    <span x-text="open === 1 ? '−' : '+'" class="text-2xl"></span>
                </button>
                <div x-show="open === 1" x-collapse class="px-6 pb-6 text-gray-600">SaaS Builder is a no‑code website builder that lets you create professional websites without any technical skills.</div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <button @click="open = open === 2 ? null : 2" class="w-full flex justify-between items-center p-6 text-left font-semibold text-gray-900 hover:text-indigo-600 transition">
                    <span>Is there a free trial?</span>
                    <span x-text="open === 2 ? '−' : '+'" class="text-2xl"></span>
                </button>
                <div x-show="open === 2" x-collapse class="px-6 pb-6 text-gray-600">Yes! You get a 14‑day free trial with full access to all Pro features. No credit card required.</div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <button @click="open = open === 3 ? null : 3" class="w-full flex justify-between items-center p-6 text-left font-semibold text-gray-900 hover:text-indigo-600 transition">
                    <span>Can I use my own domain?</span>
                    <span x-text="open === 3 ? '−' : '+'" class="text-2xl"></span>
                </button>
                <div x-show="open === 3" x-collapse class="px-6 pb-6 text-gray-600">Absolutely! All paid plans allow you to connect your own custom domain.</div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <button @click="open = open === 4 ? null : 4" class="w-full flex justify-between items-center p-6 text-left font-semibold text-gray-900 hover:text-indigo-600 transition">
                    <span>What if I need help?</span>
                    <span x-text="open === 4 ? '−' : '+'" class="text-2xl"></span>
                </button>
                <div x-show="open === 4" x-collapse class="px-6 pb-6 text-gray-600">We offer 24/7 support via live chat and email. Our team is always ready to assist you.</div>
            </div>
        </div>
    </div>
</section>