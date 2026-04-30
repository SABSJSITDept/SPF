@include('includes.frontend.header')

<!-- Google Fonts: Inter -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
    :root {
        --primary-navy: #1A5276;
        --secondary-gold: #F4D03F;
        --slate-grey: #708090;
        --light-bg: #F8FAFC;
        --border-color: #E2E8F0;
        --text-dark: #1E293B;
        --text-muted: #64748B;
    }

    body {
        font-family: 'Inter', sans-serif;
        background-color: var(--light-bg);
        color: var(--text-dark);
    }

    /* Card styling */
    .spf-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
        border: 1px solid var(--border-color);
        overflow: hidden;
    }

    /* Progress Bar */
    .wizard-progress {
        display: flex;
        justify-content: space-between;
        margin-bottom: 2rem;
        position: relative;
    }

    .wizard-progress::before {
        content: '';
        position: absolute;
        top: 20px;
        left: 0;
        width: 100%;
        height: 2px;
        background: var(--border-color);
        z-index: 1;
    }

    .progress-step {
        position: relative;
        z-index: 2;
        text-align: center;
        width: 33.33%;
    }

    .step-circle {
        width: 40px;
        height: 40px;
        background: #fff;
        border: 2px solid var(--border-color);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 8px;
        font-weight: 700;
        color: var(--text-muted);
        transition: all 0.3s ease;
    }

    .progress-step.active .step-circle {
        border-color: var(--primary-navy);
        background: var(--primary-navy);
        color: #fff;
        box-shadow: 0 0 0 4px rgba(26, 82, 118, 0.15);
    }

    .progress-step.completed .step-circle {
        border-color: #10B981;
        background: #10B981;
        color: #fff;
    }

    .step-label {
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .progress-step.active .step-label { color: var(--primary-navy); }

    /* Floating Labels */
    .field-group {
        position: relative;
        margin-bottom: 1.5rem;
    }

    .floating-label {
        position: absolute;
        top: 0.75rem;
        left: 1rem;
        pointer-events: none;
        transition: all 0.2s ease;
        color: var(--text-muted);
        background: #fff;
        padding: 0 4px;
        font-size: 0.875rem;
    }

    .input-field {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1.5px solid var(--border-color);
        border-radius: 8px;
        outline: none;
        transition: all 0.2s ease;
        font-size: 0.875rem;
    }

    .input-field:focus {
        border-color: var(--primary-navy);
        box-shadow: 0 0 0 3px rgba(26, 82, 118, 0.1);
    }

    .input-field:focus + .floating-label,
    .input-field:not(:placeholder-shown) + .floating-label {
        top: -0.6rem;
        left: 0.75rem;
        font-size: 0.75rem;
        color: var(--primary-navy);
        font-weight: 600;
    }

    .input-field::placeholder { color: transparent; }

    /* Buttons */
    .btn-navy {
        background: var(--primary-navy);
        color: #fff;
        padding: 0.75rem 2rem;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.2s ease;
    }
    .btn-navy:hover {
        background: #154360;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(26, 82, 118, 0.2);
    }

    .btn-gold {
        background: var(--secondary-gold);
        color: #1A5276;
        padding: 0.75rem 2.5rem;
        border-radius: 8px;
        font-weight: 700;
        transition: all 0.2s ease;
        border: none;
    }
    .btn-gold:hover {
        background: #F1C40F;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(244, 208, 63, 0.3);
    }

    .btn-outline {
        border: 1.5px solid var(--border-color);
        color: var(--text-muted);
        padding: 0.75rem 2rem;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.2s ease;
    }
    .btn-outline:hover {
        border-color: var(--slate-grey);
        color: var(--text-dark);
        background: #F1F5F9;
    }

    /* Validation indicator */
    .valid-indicator {
        position: absolute;
        right: 12px;
        top: 0.85rem;
        display: none;
    }
    .input-valid + .valid-indicator { display: block; color: #10B981; }

    /* Readonly/Locked Fields */
    select[readonly] {
        pointer-events: none !important;
        opacity: 0.8;
        cursor: not-allowed;
    }

    /* Step transition animations */
    .form-step { display: none; }
    .form-step.active { display: block; animation: fadeIn 0.4s ease-out; }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* File upload design */
    .file-dropzone {
        border: 2px dashed var(--border-color);
        border-radius: 12px;
        padding: 2rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .file-dropzone:hover {
        border-color: var(--primary-navy);
        background: rgba(26, 82, 118, 0.02);
    }

    /* Suggestion List */
    .suggestions-list {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: #fff;
        border: 1px solid var(--border-color);
        z-index: 50;
        border-radius: 0 0 8px 8px;
        max-height: 200px;
        overflow-y: auto;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }

    /* ── Scrollable Select Dropdown ─────────────────────────────────── */
    select.input-field {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='%2364748B' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 12px center;
        background-size: 14px;
        cursor: pointer;
        padding-right: 2.5rem;
    }

    /* Expanded state: floats as a scrollable overlay */
    select.input-field[size]:not([size="1"]) {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        max-height: 220px;
        overflow-y: auto;
        z-index: 500;
        background-image: none;
        background-color: #fff;
        border-color: var(--primary-navy) !important;
        box-shadow: 0 0 0 3px rgba(26,82,118,0.12), 0 8px 24px rgba(0,0,0,0.12);
        border-radius: 8px;
        scrollbar-width: thin;
        scrollbar-color: var(--primary-navy) #f1f5f9;
    }

    select.input-field[size]:not([size="1"])::-webkit-scrollbar { width: 5px; }
    select.input-field[size]:not([size="1"])::-webkit-scrollbar-track {
        background: #f1f5f9; border-radius: 3px;
    }
    select.input-field[size]:not([size="1"])::-webkit-scrollbar-thumb {
        background: var(--primary-navy); border-radius: 3px;
    }

    select.input-field option { padding: 10px 16px; }
    select.input-field option:checked { background: rgba(26,82,118,0.1); color: var(--primary-navy); }
</style>

<div class="max-w-4xl mx-auto px-4 py-12">
    <div class="mb-8 text-center">
        <h2 class="text-3xl font-bold text-[#1A5276] mb-2">SPF Professional Registration</h2>
        <p class="text-slate-500">Join the global network of Sadhumargi Professionals</p>
    </div>

    <!-- Wizard Container -->
    <div class="spf-card p-8 md:p-12">
        
        <!-- Progress Bar -->
        <div class="wizard-progress">
            <div class="progress-step active" id="step1Header">
                <div class="step-circle">1</div>
                <div class="step-label">Personal</div>
            </div>
            <div class="progress-step" id="step2Header">
                <div class="step-circle">2</div>
                <div class="step-label">Professional</div>
            </div>
            <div class="progress-step" id="step3Header">
                <div class="step-circle">3</div>
                <div class="step-label">Interests</div>
            </div>
        </div>

        <form id="spfForm" enctype="multipart/form-data" novalidate>
            @csrf

            <!-- Profile Update Notice -->
            <div id="profileUpdateNotice" class="hidden mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <div class="flex gap-3">
                    <i class="fas fa-info-circle text-blue-600 mt-0.5"></i>
                    <div class="text-sm text-blue-800">
                        <p class="font-semibold mb-1">Profile Information Locked</p>
                        <p>The profile details below have been auto-filled from your record. To edit these fields, please update your profile details in the <strong>Sadhumargi App</strong> first.</p>
                    </div>
                </div>
            </div>

            <!-- STEP 1: PERSONAL DETAILS -->
            <div class="form-step active" id="step1">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Mobile Number -->
                    <div class="field-group">
                        <input type="tel" id="mobile" name="mobile" class="input-field" placeholder=" " maxlength="10" required />
                        <label class="floating-label">Mobile Number (Primary) *</label>
                        <span id="mobileLoader" class="hidden absolute right-3 top-3">
                            <svg class="animate-spin h-5 w-5 text-[#1A5276]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                            </svg>
                        </span>
                        <div class="valid-indicator" id="mobileValidIcon"><i class="fas fa-check-circle"></i></div>
                    </div>

                    <!-- Membership ID (MID) -->
                    <div class="field-group">
                        <input type="text" id="mid" name="mid" class="input-field bg-slate-50" readonly placeholder=" " />
                        <label class="floating-label">Membership ID (Auto-filled)</label>
                        <div class="valid-indicator" id="midValidIcon"><i class="fas fa-check-circle"></i></div>
                    </div>

                    <!-- Full Name -->
                    <div class="field-group">
                        <input type="text" id="fullName" name="full_name" class="input-field bg-slate-50" placeholder=" " required />
                        <label class="floating-label">Full Name *</label>
                        <ul id="nameSuggestions" class="suggestions-list hidden"></ul>
                    </div>

                    <!-- Father's/Husband's Name -->
                    <div class="field-group">
                        <input type="text" id="fatherName" name="father_name" class="input-field bg-slate-50" placeholder=" " />
                        <label class="floating-label">Father's / Husband's Name</label>
                    </div>

                    <!-- DOB -->
                    <div class="field-group">
                        <input type="date" id="dob" name="dob" class="input-field" placeholder=" " required />
                        <label class="floating-label">Date of Birth *</label>
                    </div>

                    <!-- Age -->
                    <div class="field-group">
                        <input type="number" id="age" name="age" class="input-field bg-slate-50" readonly placeholder=" " />
                        <label class="floating-label">Current Age</label>
                    </div>

                    <!-- Email -->
                    <div class="field-group">
                        <input type="email" id="email" name="email" class="input-field bg-slate-50" placeholder=" " />
                        <label class="floating-label">Email Address</label>
                    </div>

                    <!-- Gender -->
                    <div class="field-group">
                        <select id="gender" name="gender" class="input-field bg-slate-50" required>
                            <option value="">Select Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                        <label class="floating-label">Gender *</label>
                    </div>

                    <!-- State -->
                    <div class="field-group">
                        <select id="state" name="state" class="input-field bg-slate-50" required>
                            <option value="">Select State</option>
                        </select>
                        <label class="floating-label">State *</label>
                    </div>

                    <!-- City -->
                    <div class="field-group">
                        <select id="city" name="city" class="input-field bg-slate-50" required>
                            <option value="">Select City</option>
                        </select>
                        <label class="floating-label">City *</label>
                    </div>

                    <!-- Anchal -->
                    <div class="field-group">
                        <input type="text" id="anchal" name="anchal" class="input-field bg-slate-50" readonly placeholder=" " />
                        <label class="floating-label">Anchal</label>
                    </div>

                    <!-- Local Sangh -->
                    <div class="field-group">
                        <select id="localSanghSelect" name="local_sangh_id" class="input-field bg-slate-50">
                            <option value="">Select Branch</option>
                        </select>
                        <label class="floating-label">Local Sangh / Branch (Optional)</label>
                    </div>
                </div>

                <div class="flex justify-end mt-8">
                    <button type="button" onclick="nextStep(2)" class="btn-navy">Next: Professional Details <i class="fas fa-arrow-right ml-2"></i></button>
                </div>
            </div>

            <!-- STEP 2: PROFESSIONAL DETAILS -->
            <div class="form-step" id="step2">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Profession -->
                    <div class="field-group">
                        <select id="profession" name="profession" class="input-field" required>
                            <option value="">Select Profession</option>
                        </select>
                        <label class="floating-label">Profession *</label>
                    </div>

                    <!-- Category -->
                    <div class="field-group">
                        <select id="professionalCategory" name="professional_category" class="input-field">
                            <option value="">Select Category</option>
                        </select>
                        <label class="floating-label">Professional Category</label>
                    </div>

                    <!-- Working Status -->
                    <div class="field-group md:col-span-2">
                        <label class="text-sm font-semibold text-slate-700 mb-4 block">Current Working Status *</label>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                            <label class="relative flex items-center p-3 border rounded-lg cursor-pointer hover:bg-slate-50 transition">
                                <input type="radio" name="working_status" value="working" class="w-4 h-4 text-[#1A5276]">
                                <span class="ml-2 text-sm">Working</span>
                            </label>
                            <label class="relative flex items-center p-3 border rounded-lg cursor-pointer hover:bg-slate-50 transition">
                                <input type="radio" name="working_status" value="non_working" class="w-4 h-4 text-[#1A5276]">
                                <span class="ml-2 text-sm">Non-working</span>
                            </label>
                            <label class="relative flex items-center p-3 border rounded-lg cursor-pointer hover:bg-slate-50 transition">
                                <input type="radio" name="working_status" value="self_employed" class="w-4 h-4 text-[#1A5276]">
                                <span class="ml-2 text-sm">Self-employed</span>
                            </label>
                            <label class="relative flex items-center p-3 border rounded-lg cursor-pointer hover:bg-slate-50 transition">
                                <input type="radio" name="working_status" value="service" class="w-4 h-4 text-[#1A5276]">
                                <span class="ml-2 text-sm">Service</span>
                            </label>
                            <label class="relative flex items-center p-3 border rounded-lg cursor-pointer hover:bg-slate-50 transition">
                                <input type="radio" name="working_status" value="looking_for_opportunities" class="w-4 h-4 text-[#1A5276]">
                                <span class="ml-2 text-sm">Looking for opportunities</span>
                            </label>
                        </div>
                    </div>

                    <!-- Document Type -->
                    <div class="field-group">
                        <select id="documentType" name="document_type" class="input-field" required>
                            <option value="">Select Document Type</option>
                            <option value="Professional degree">Professional degree</option>
                            <option value="LinkedIn profile">LinkedIn profile</option>
                            <option value="Business card">Business card</option>
                        </select>
                        <label class="floating-label">Document Type *</label>
                    </div>

                    <!-- Document Upload -->
                    <div class="field-group md:col-span-2">
                        <label class="text-sm font-semibold text-slate-700 mb-2 block">Highest Qualification Certificate (PDF, 2MB–5MB) *</label>
                        <div class="file-dropzone" onclick="document.getElementById('fileUpload').click()">
                            <input type="file" id="fileUpload" name="file" accept=".pdf" class="hidden" />
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-[#1A5276] mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                <p class="text-sm font-medium text-slate-600" id="fileNameDisplay">Drag and drop or click to upload</p>
                                <p class="text-xs text-slate-400 mt-1">Only PDF format supported</p>
                            </div>
                        </div>
                        <span id="fileError" class="text-xs text-red-500 mt-2 block h-4"></span>
                    </div>
                </div>

                <div class="flex justify-between mt-8">
                    <button type="button" onclick="prevStep(1)" class="btn-outline"><i class="fas fa-arrow-left mr-2"></i> Previous</button>
                    <button type="button" onclick="nextStep(3)" class="btn-navy">Next: Community Info <i class="fas fa-arrow-right ml-2"></i></button>
                </div>
            </div>

            <!-- STEP 3: COMMUNITY & INTERESTS -->
            <div class="form-step" id="step3">
                <div class="space-y-8">
                    <!-- Sadhumargi Membership -->
                    <div>
                        <label class="text-sm font-semibold text-slate-700 mb-4 block">Do you belong to Sadhumargi family? *</label>
                        <div class="flex gap-6">
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="radio" name="sadhumargi" value="yes" class="w-4 h-4 text-[#1A5276]">
                                <span class="ml-2">Yes</span>
                            </label>
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="radio" name="sadhumargi" value="no" class="w-4 h-4 text-[#1A5276]">
                                <span class="ml-2">No</span>
                            </label>
                        </div>
                    </div>

                    <!-- Source of Info -->
                    <div>
                        <label class="text-sm font-semibold text-slate-700 mb-4 block">How did you come to know about SPF?</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <label class="flex items-center p-3 border rounded-lg hover:bg-slate-50 cursor-pointer">
                                <input type="radio" name="source" value="stall_counter" class="w-4 h-4 text-[#1A5276]">
                                <span class="ml-2 text-sm">Chaturmas Stall/Counter</span>
                            </label>
                            <label class="flex items-center p-3 border rounded-lg hover:bg-slate-50 cursor-pointer">
                                <input type="radio" name="source" value="social_media" class="w-4 h-4 text-[#1A5276]">
                                <span class="ml-2 text-sm">Social Media</span>
                            </label>
                            <label class="flex items-center p-3 border rounded-lg hover:bg-slate-50 cursor-pointer">
                                <input type="radio" name="source" value="friend_acquaintance" class="w-4 h-4 text-[#1A5276]">
                                <span class="ml-2 text-sm">Friend / Acquaintance</span>
                            </label>
                            <label class="flex items-center p-3 border rounded-lg hover:bg-slate-50 cursor-pointer">
                                <input type="radio" name="source" value="shramnopasak" class="w-4 h-4 text-[#1A5276]">
                                <span class="ml-2 text-sm">Shramnopasak</span>
                            </label>
                            <label class="flex items-center p-3 border rounded-lg hover:bg-slate-50 cursor-pointer">
                                <input type="radio" name="source" value="other" id="sourceOther" class="w-4 h-4 text-[#1A5276]" onchange="toggleOtherInput('sourceOtherText', this.checked)">
                                <span class="ml-2 text-sm">Other</span>
                            </label>
                        </div>
                        <div id="sourceOtherText" class="hidden mt-3">
                            <input type="text" name="source_other" id="sourceOtherInput" class="input-field" placeholder="Please specify..." />
                        </div>
                    </div>

                    <!-- Objectives -->
                    <div>
                        <label class="text-sm font-semibold text-slate-700 mb-4 block">What is your objective behind joining SPF? *</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                            <label class="flex items-center p-3 border rounded-lg hover:bg-slate-50 cursor-pointer">
                                <input type="checkbox" name="objectives[]" value="spiritual_development" class="w-4 h-4 text-[#1A5276] rounded">
                                <span class="ml-2 text-sm">Spiritual Development</span>
                            </label>
                            <label class="flex items-center p-3 border rounded-lg hover:bg-slate-50 cursor-pointer">
                                <input type="checkbox" name="objectives[]" value="social_development" class="w-4 h-4 text-[#1A5276] rounded">
                                <span class="ml-2 text-sm">Social Development</span>
                            </label>
                            <label class="flex items-center p-3 border rounded-lg hover:bg-slate-50 cursor-pointer">
                                <input type="checkbox" name="objectives[]" value="personality_development" class="w-4 h-4 text-[#1A5276] rounded">
                                <span class="ml-2 text-sm">Personality Development</span>
                            </label>
                            <label class="flex items-center p-3 border rounded-lg hover:bg-slate-50 cursor-pointer">
                                <input type="checkbox" name="objectives[]" value="professional_development" class="w-4 h-4 text-[#1A5276] rounded">
                                <span class="ml-2 text-sm">Professional Development</span>
                            </label>
                            <label class="flex items-center p-3 border rounded-lg hover:bg-slate-50 cursor-pointer">
                                <input type="checkbox" name="objectives[]" value="skill_development" class="w-4 h-4 text-[#1A5276] rounded">
                                <span class="ml-2 text-sm">Skill Development</span>
                            </label>
                            <label class="flex items-center p-3 border rounded-lg hover:bg-slate-50 cursor-pointer">
                                <input type="checkbox" name="objectives[]" value="other" id="objectiveOther" class="w-4 h-4 text-[#1A5276] rounded" onchange="toggleOtherInput('objectiveOtherText', this.checked)">
                                <span class="ml-2 text-sm">Other</span>
                            </label>
                        </div>
                        <div id="objectiveOtherText" class="hidden mt-3">
                            <input type="text" name="objective_other" id="objectiveOtherInput" class="input-field" placeholder="Please specify..." />
                        </div>
                    </div>

                    <!-- Hobbies & Referral -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="field-group">
                            <textarea id="hobbies" name="hobbies" class="input-field min-h-[100px]" placeholder=" "></textarea>
                            <label class="floating-label">Hobbies / Areas of Interest</label>
                        </div>
                        <div class="field-group">
                            <textarea id="referral" name="referral" class="input-field min-h-[100px]" placeholder=" "></textarea>
                            <label class="floating-label">Referral's Name / Details</label>
                        </div>
                    </div>
                </div>

                <div class="flex justify-between mt-8">
                    <button type="button" onclick="prevStep(2)" class="btn-outline"><i class="fas fa-arrow-left mr-2"></i> Previous</button>
                    <button type="submit" id="submitBtn" class="btn-gold">Confirm & Submit Form <i class="fas fa-paper-plane ml-2"></i></button>
                </div>
            </div>

        </form>
            </div>
        </div>
    </div>
</div>

<script>
    const apiBase = "https://apiv1.sadhumargi.com/api";
    const token   = "vPW6doIdkAdf";

    // ─── Toggle "Other" text input visibility ─────────────────────────────────
    function toggleOtherInput(containerId, show) {
        const container = document.getElementById(containerId);
        if (!container) return;
        if (show) {
            container.classList.remove('hidden');
            container.querySelector('input').focus();
        } else {
            container.classList.add('hidden');
            container.querySelector('input').value = '';
        }
    }

    // ─── Scrollable Select Dropdowns ──────────────────────────────────────────
    function initScrollableDropdowns() {
        document.querySelectorAll('select.input-field').forEach(function (sel) {
            if (sel.dataset.scrollInit) return; // avoid duplicate listeners
            sel.dataset.scrollInit = '1';

            sel.addEventListener('mousedown', function (e) {
                if (this.size <= 1) {
                    e.preventDefault();
                    var count = Math.min(Math.max(this.options.length, 1), 8);
                    this.size = count;
                    this.focus();
                    var self = this;
                    function onOutside(ev) {
                        // Keep dropdown open when clicking one of its options;
                        // close only when click is outside the select.
                        if (!self.contains(ev.target)) {
                            self.size = 1;
                            document.removeEventListener('mousedown', onOutside, true);
                        }
                    }
                    setTimeout(function () {
                        document.addEventListener('mousedown', onOutside, true);
                    }, 0);
                }
            });

            sel.addEventListener('change', function () { this.size = 1; });
            sel.addEventListener('blur', function () { this.size = 1; });
            sel.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') { this.size = 1; this.blur(); }
            });
        });
    }

    // Hide source "Other" text box when another radio is selected
    document.addEventListener('DOMContentLoaded', function () {
        // Init scrollable dropdowns
        initScrollableDropdowns();

        document.querySelectorAll('input[name="source"]').forEach(function (radio) {
            radio.addEventListener('change', function () {
                if (this.value !== 'other') {
                    toggleOtherInput('sourceOtherText', false);
                }
            });
        });
    });

    // ─── Wizard Controller ───────────────────────────────────────────────────
    function nextStep(step) {
        if (!validateCurrentStep(step - 1)) return;

        // Hide all steps
        document.querySelectorAll('.form-step').forEach(s => s.classList.remove('active'));
        // Show next step
        document.getElementById(`step${step}`).classList.add('active');
        // Update header
        document.querySelectorAll('.progress-step').forEach((el, idx) => {
            if (idx + 1 < step) el.classList.add('completed');
            if (idx + 1 === step) el.classList.add('active');
            else el.classList.remove('active');
        });
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function prevStep(step) {
        document.querySelectorAll('.form-step').forEach(s => s.classList.remove('active'));
        document.getElementById(`step${step}`).classList.add('active');
        document.querySelectorAll('.progress-step').forEach((el, idx) => {
            if (idx + 1 >= step) el.classList.remove('completed');
            if (idx + 1 === step) el.classList.add('active');
            else el.classList.remove('active');
        });
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function validateCurrentStep(step) {
        let isValid = true;
        const currentStepEl = document.getElementById(`step${step}`);
        const requiredFields = currentStepEl.querySelectorAll('[required]');
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.classList.add('border-red-400');
                isValid = false;
            } else {
                field.classList.remove('border-red-400');
            }
        });

        // Special validation for Step 2: File upload
        if (step === 2 && !selectedFile) {
            document.getElementById('fileError').textContent = 'Highest qualification document is required.';
            isValid = false;
        }

        if (!isValid) {
            Swal.fire({
                icon: 'warning',
                title: 'Required Fields',
                text: 'Please fill in all mandatory fields (*) before proceeding.',
                confirmButtonColor: '#1A5276'
            });
        }
        return isValid;
    }

    // ─── DOB → Auto-calculate Age ──────────────────────────────────────────────
    document.getElementById('dob').addEventListener('change', function () {
        const dob = new Date(this.value);
        if (isNaN(dob)) return;
        const today = new Date();
        let age = today.getFullYear() - dob.getFullYear();
        const m = today.getMonth() - dob.getMonth();
        if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) age--;
        document.getElementById('age').value = age >= 0 ? age : '';
        this.classList.remove('border-red-400');
    });

    // ─── Fill form fields from API profile object ──────────────────────────────
    function fillProfileFields(profile) {
        // Show the profile update notice
        document.getElementById('profileUpdateNotice').classList.remove('hidden');
        
        // Fields to lock
        const fieldsToLock = [
            'fullName', 'fatherName', 'email', 'gender', 'state', 'city', 'localSanghSelect'
        ];
        
        // Lock all fields that should be read-only
        fieldsToLock.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field) {
                field.setAttribute('readonly', 'readonly');
                field.style.cursor = 'not-allowed';
                field.style.opacity = '0.8';
                field.classList.add('cursor-not-allowed');
            }
        });
        
        document.getElementById('fullName').value = `${profile.first_name ?? ''} ${profile.last_name ?? ''}`.trim();
        document.getElementById('mid').value = profile.member_id ?? '';
        
        // Visual indicator for MID
        if (profile.member_id) {
            document.getElementById('midValidIcon').style.display = 'block';
            document.getElementById('midValidIcon').style.color = '#10B981';
        }

        const fatherName = (profile.guardian_type === 'Father')
            ? (profile.guardian_name || '')
            : (profile.father_name || profile.fathers_name || profile.guardian_name || '');
        document.getElementById('fatherName').value = fatherName ? fatherName.toUpperCase() : '';

        if (profile.birth_day) {
            document.getElementById('dob').value = profile.birth_day;
            const dob = new Date(profile.birth_day);
            const today = new Date();
            let age = today.getFullYear() - dob.getFullYear();
            const m = today.getMonth() - dob.getMonth();
            if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) age--;
            document.getElementById('age').value = age >= 0 ? age : '';
        }

        document.getElementById('email').value = profile.email_address ?? '';
        const gender = (profile.gender ?? '').toLowerCase();
        const genderEl = document.getElementById('gender');
        genderEl.value = ['male', 'female'].includes(gender) ? gender : (gender ? 'other' : '');

        // Location data matching
        if (profile.local_sangh_id) {
            const branch = allBranches.find(b => b.id == profile.local_sangh_id);
            if (branch) {
                const stateId = branch.state_id ?? stateByName[branch.state_name] ?? '';
                document.getElementById('state').value = stateId;
                populateCities();
                setTimeout(() => {
                    const cityId = cityByName[`${stateId}_${branch.city}`] ?? '';
                    document.getElementById('city').value = cityId;
                    populateAnchal();
                    // Set branch dropdown to selected branch
                    setTimeout(() => {
                        document.getElementById('localSanghSelect').value = branch.id;
                        // Lock the fields after they're populated
                        fieldsToLock.forEach(fieldId => {
                            const field = document.getElementById(fieldId);
                            if (field) {
                                field.setAttribute('readonly', 'readonly');
                                field.style.cursor = 'not-allowed';
                                field.style.opacity = '0.8';
                                field.classList.add('cursor-not-allowed');
                            }
                        });
                    }, 100);
                }, 300);
            }
        }

        // Trigger floating label update
        document.querySelectorAll('.input-field').forEach(el => {
            if (el.value) el.classList.add('not-placeholder-shown');
        });
    }

    // ─── Mobile → Fetch Member Details ─────────────────────────────────────────
    document.getElementById('mobile').addEventListener('blur', function () {
        const mobile = this.value.trim();
        if (mobile.length !== 10) return;

        // Show loading overlay
        const overlay = document.getElementById('loadingOverlay');
        overlay.classList.remove('hidden');
        setTimeout(() => overlay.classList.add('active'), 10);
        document.body.classList.add('overlay-active');
        
        document.getElementById('loaderText').textContent = 'Searching Profile...';
        document.getElementById('loaderSubtext').textContent = 'Please wait';

        const loadingStartTime = Date.now();
        const minLoadingDuration = 3000; // 3 seconds minimum

        const body = new URLSearchParams();
        body.append('mobile_number', mobile);

        fetch(`${apiBase}/fetch-profiles`, {
            method: 'POST',
            headers: { 'Accept': 'application/json', 'Authorization': `Bearer ${token}` },
            body: body
        })
        .then(res => res.json())
        .then(data => {
            if (data.profiles && data.profiles.length > 0) {
                fillProfileFields(data.profiles[0]);
                document.getElementById('mobileValidIcon').style.display = 'block';
                document.getElementById('mobileValidIcon').style.color = '#10B981';
                
                if (data.profiles.length > 1) {
                    const list = document.getElementById('nameSuggestions');
                    list.innerHTML = '';
                    data.profiles.forEach(p => {
                        const li = document.createElement('li');
                        li.textContent = `${p.first_name} ${p.last_name} (${p.member_id})`;
                        li.className = 'p-3 hover:bg-slate-50 cursor-pointer text-sm border-b last:border-0';
                        li.addEventListener('click', () => { fillProfileFields(p); list.classList.add('hidden'); });
                        list.appendChild(li);
                    });
                    list.classList.remove('hidden');
                }
            } else {
                Swal.fire('Identity Check', 'No profile found for this number. Please fill the details manually.', 'info');
            }
        })
        .catch(err => {
            console.error('Error fetching profile:', err);
            Swal.fire('Error', 'Failed to fetch profile. Please try again.', 'error');
        })
        .finally(() => {
            // Ensure loading shows for minimum 3 seconds
            const elapsedTime = Date.now() - loadingStartTime;
            const remainingTime = Math.max(0, minLoadingDuration - elapsedTime);
            
            setTimeout(() => {
                const overlay = document.getElementById('loadingOverlay');
                overlay.classList.remove('active');
                setTimeout(() => overlay.classList.add('hidden'), 300);
                document.body.classList.remove('overlay-active');
            }, remainingTime);
        });
    });

    // ─── Location Logic ───────────────────────────────────────────────────────
    let allBranches = [], allCities = [], locationData = {}, stateByName = {}, cityByName = {};
    let selectedStateId = '', selectedAnchalId = '';

    function buildLocationData() {
        allCities.forEach(c => {
            if (!locationData[c.state_id]) locationData[c.state_id] = { name: c.state_name, cities: {} };
            locationData[c.state_id].cities[c.city_id] = { name: c.city_name, anchal_name: c.anchal_name, anchal_id: c.anchal_id };
            stateByName[c.state_name] = c.state_id;
            cityByName[`${c.state_id}_${c.city_name}`] = c.city_id;
        });
        const stateSelect = document.getElementById('state');
        Object.entries(locationData).sort((a,b) => a[1].name.localeCompare(b[1].name)).forEach(([sid, data]) => {
            stateSelect.add(new Option(data.name, sid));
        });
        initScrollableDropdowns();
    }

    function populateCities() {
        const sid = document.getElementById('state').value;
        const citySelect = document.getElementById('city');
        citySelect.innerHTML = '<option value="">Select City</option>';
        selectedStateId = sid;
        // Clear local sangh when state changes
        const branchSelect = document.getElementById('localSanghSelect');
        branchSelect.innerHTML = '<option value="">Select Branch</option>';
        document.getElementById('anchal').value = '';
        selectedAnchalId = '';
        if (!sid || !locationData[sid]) return;
        Object.entries(locationData[sid].cities).sort((a,b) => a[1].name.localeCompare(b[1].name)).forEach(([cid, data]) => {
            citySelect.add(new Option(data.name, cid));
        });
        initScrollableDropdowns();
    }

    function populateAnchal() {
        const sid = document.getElementById('state').value, cid = document.getElementById('city').value;
        const data = locationData[sid]?.cities?.[cid];
        document.getElementById('anchal').value = data?.anchal_name || '';
        selectedAnchalId = data?.anchal_id || '';
        populateBranches();
    }
    
    function populateBranches() {
        const branchSelect = document.getElementById('localSanghSelect');
        branchSelect.innerHTML = '<option value="">Select Branch</option>';
        
        if (!selectedAnchalId) return;
        
        // Filter branches by anchal_id
        const branchesForAnchal = allBranches.filter(b => b.anchal_id == selectedAnchalId);
        
        if (branchesForAnchal.length === 0) {
            branchSelect.add(new Option('No branches available', ''));
            return;
        }
        
        // Sort branches by name and add to dropdown
        branchesForAnchal.sort((a, b) => (a.branch_name || '').localeCompare(b.branch_name || '')).forEach(b => {
            branchSelect.add(new Option(`${b.branch_name}${b.city ? ' (' + b.city + ')' : ''}`, b.id));
        });
        initScrollableDropdowns();
        console.log('Branches for anchal:', selectedAnchalId, branchesForAnchal.length, 'loaded');
    }

    // ─── Profession Logic ─────────────────────────────────────────────────────
    fetch('/api/categories').then(res => res.json()).then(cats => {
        const s = document.getElementById('profession');
        cats.forEach(c => s.add(new Option(c.category_name, c.id)));
        initScrollableDropdowns();
    });

    document.getElementById('profession').addEventListener('change', function() {
        const subS = document.getElementById('professionalCategory');
        subS.innerHTML = '<option value="">Select Category</option>';
        if (!this.value) return;
        fetch(`/api/sub-categories/${this.value}`).then(res => res.json()).then(subs => {
            subs.forEach(sub => subS.add(new Option(sub.sub_category_name, sub.sub_category_name)));
        });
    });

    // ─── Init Loaders ─────────────────────────────────────────────────────────
    Promise.all([
        fetch('https://mrm.sadhumargi.org/api/cities').then(r => r.json()),
        fetch('https://mrm.sadhumargi.org/api/branches').then(r => r.json())
    ]).then(([citiesData, branchesData]) => {
        allCities = citiesData.cities || [];
        allBranches = branchesData.branches || [];
        console.log('Cities loaded:', allCities.length);
        console.log('Branches loaded:', allBranches.length);
        if (allBranches.length > 0) {
            console.log('Sample branch:', allBranches[0]);
        }
        buildLocationData();
    }).catch(err => {
        console.error('Error loading location data:', err);
        Swal.fire('Error', 'Could not load location data. Please refresh the page.', 'error');
    });

    document.getElementById('state').addEventListener('change', populateCities);
    document.getElementById('city').addEventListener('change', populateAnchal);

    // ─── File Validation ──────────────────────────────────────────────────────
    let selectedFile = null;
    document.getElementById('fileUpload').addEventListener('change', function() {
        const file = this.files[0], err = document.getElementById('fileError'), disp = document.getElementById('fileNameDisplay');
        selectedFile = null;
        if (!file) { disp.textContent = 'Drag and drop or click to upload'; return; }
        if (file.type !== 'application/pdf') { 
            err.textContent = 'Only PDF files are allowed.'; 
            this.value = '';
            return; 
        }
        const sizeMB = file.size / (1024 * 1024);
        if (sizeMB < 2 || sizeMB > 5) { 
            err.textContent = 'File size must be between 2MB and 5MB.'; 
            this.value = '';
            return; 
        }
        err.textContent = '';
        selectedFile = file;
        disp.textContent = file.name;
    });

    // ─── Form Submit ──────────────────────────────────────────────────────────
    document.getElementById('spfForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        if (!selectedFile) {
            Swal.fire({ icon: 'error', title: 'Missing Document', text: 'Please upload your highest qualification certificate (PDF).' });
            return;
        }

        const overlay = document.getElementById('loadingOverlay');
        overlay.classList.remove('hidden');
        setTimeout(() => overlay.classList.add('active'), 10);
        document.body.classList.add('overlay-active');
        
        document.getElementById('loaderText').textContent = 'Submitting Registration...';
        document.getElementById('loaderSubtext').textContent = 'Please don\'t close this window';

        const formData = new FormData(this);
        formData.append('state', selectedStateId);
        formData.append('anchal', selectedAnchalId);
        
        const loadingStartTime = Date.now();
        const minLoadingDuration = 3000; // 3 seconds minimum
        
        const hideLoader = () => {
            const elapsedTime = Date.now() - loadingStartTime;
            const remainingTime = Math.max(0, minLoadingDuration - elapsedTime);
            
            setTimeout(() => {
                overlay.classList.remove('active');
                setTimeout(() => overlay.classList.add('hidden'), 300);
                document.body.classList.remove('overlay-active');
            }, remainingTime);
        };
        
        try {
            const res = await fetch('/api/register', {
                method: 'POST',
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                body: formData
            });
            const data = await res.json();
            if (data.status) {
                hideLoader();
                
                Swal.fire({ 
                    icon: 'success', 
                    title: 'Registration Successful!', 
                    text: 'Your SPF registration has been submitted successfully. Our team will review your application.', 
                    confirmButtonColor: '#1A5276' 
                }).then(() => {
                    window.location.reload();
                });
            } else {
                hideLoader();
                Swal.fire('Error', data.message || 'Submission failed. Please check all fields.', 'error');
            }
        } catch (err) {
            hideLoader();
            Swal.fire('Error', 'Something went wrong during submission. Please try again later.', 'error');
        }
    });

    // Outside click to hide name suggestions
    document.addEventListener('click', (e) => {
        if (!e.target.closest('#fullName') && !e.target.closest('#nameSuggestions')) {
            document.getElementById('nameSuggestions').classList.add('hidden');
        }
    });

    // Prevent changes to locked fields when profile is loaded
    const lockedFields = ['fullName', 'fatherName', 'email', 'gender', 'state', 'city', 'localSanghSelect'];
    
    lockedFields.forEach(fieldId => {
        const field = document.getElementById(fieldId);
        if (field) {
            // Prevent change events on locked fields
            field.addEventListener('change', function(e) {
                if (this.hasAttribute('readonly')) {
                    e.preventDefault();
                    return false;
                }
            });
            
            // Show tooltip on hover for locked fields
            field.addEventListener('mouseenter', function() {
                if (this.hasAttribute('readonly')) {
                    this.title = 'This field is locked. Update your profile in the Sadhumargi App to make changes.';
                }
            });
        }
    });
</script>

@include('includes.frontend.footer')
