<section id="faq" class="py-20 bg-white">
    <div class="container mx-auto px-4">
        {{-- Section Header --}}
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="inline-block px-4 py-1 bg-blue-100 text-blue-600 text-sm font-semibold rounded-full mb-4">
                FAQ
            </span>
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                Frequently Asked Questions
            </h2>
            <p class="text-lg text-gray-600">
                Got questions? We've got answers
            </p>
        </div>

        {{-- FAQ Items --}}
        <div class="max-w-3xl mx-auto space-y-4" x-data="{ open: null }">
            @php
                $faqs = [
                    [
                        'question' => 'Do I need coding skills to build a website?',
                        'answer' => 'No, absolutely not! Our drag and drop builder is designed for everyone. You can build a professional website without any coding knowledge.'
                    ],
                    [
                        'question' => 'Is hosting included in the plan?',
                        'answer' => 'Yes, all our plans include hosting with 99.9% uptime guarantee, free SSL certificates, and automatic backups.'
                    ],
                    [
                        'question' => 'Can I use my own domain name?',
                        'answer' => 'Yes, you can use your own domain name with all our plans. We also offer free domain registration with annual plans.'
                    ],
                    [
                        'question' => 'What happens to my website if I cancel?',
                        'answer' => 'You can export your website data at any time. If you cancel, we keep your data for 30 days, giving you time to decide.'
                    ],
                    [
                        'question' => 'Is there a money-back guarantee?',
                        'answer' => 'Yes, we offer a 14-day money-back guarantee. If you\'re not satisfied, we\'ll give you a full refund.'
                    ],
                    [
                        'question' => 'Do you offer support?',
                        'answer' => 'Yes, we offer email and chat support for all plans. Priority support is included in Professional and Enterprise plans.'
                    ],
                ]
            @endphp

            @foreach($faqs as $index => $faq)
                <div class="border border-gray-200 rounded-xl overflow-hidden">
                    <button @click="open = open === {{ $index }} ? null : {{ $index }}" 
                            class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-gray-50 transition-colors">
                        <span class="font-medium text-gray-900">{{ $faq['question'] }}</span>
                        <svg class="w-5 h-5 text-gray-500 transition-transform duration-300" 
                             :class="open === {{ $index }} ? 'rotate-180' : ''" 
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    
                    <div x-show="open === {{ $index }}" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 transform -translate-y-2"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         class="px-6 pb-4">
                        <p class="text-gray-600 leading-relaxed">{{ $faq['answer'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Still Have Questions --}}
        <div class="text-center mt-12">
            <p class="text-gray-600 mb-4">Still have questions?</p>
            <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 text-indigo-600 font-medium hover:text-indigo-800 transition-colors">
                Contact Support
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                </svg>
            </a>
        </div>
    </div>
</section>