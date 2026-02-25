<div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm mb-6">

    <div class="flex items-start justify-between gap-4">

        <div>

            <h3 class="text-base font-semibold text-slate-900">
                SACDEV Review Actions
            </h3>

            <p class="mt-1 text-sm text-slate-600">
                Review the president registration and take action if it is pending review.
            </p>

        </div>


        {{-- Status indicator --}}
        <div class="flex items-center gap-2 text-sm font-medium text-slate-800">

            <span class="flex h-6 w-6 items-center justify-center rounded-full bg-slate-100">
                <span class="h-2.5 w-2.5 rounded-full
                    {{
                        match($registration->status)
                        {
                            'submitted_to_sacdev' => 'bg-amber-500',
                            'approved_by_sacdev' => 'bg-emerald-500',
                            'returned' => 'bg-rose-500',
                            default => 'bg-slate-400'
                        }
                    }}">
                </span>
            </span>

            <span>
                {{
                    match($registration->status)
                    {
                        'submitted_to_sacdev' => 'Pending SACDEV Review',
                        'approved_by_sacdev' => 'Approved',
                        'returned' => 'Returned to Organization',
                        default => ucfirst(str_replace('_',' ', $registration->status ?? 'Draft'))
                    }
                }}
            </span>

        </div>

    </div>



    {{-- APPROVED --}}
    @if($registration->status === 'approved_by_sacdev')

        <div class="mt-5 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-900">

            <div class="font-semibold">
                Submission Approved
            </div>

            <div class="text-sm mt-1">
                This submission has been approved and is now locked.
            </div>

        </div>

        @if($registration->sacdev_reviewed_at)
            <div class="mt-3 text-xs text-slate-500">
                Approved on {{ \Carbon\Carbon::parse($registration->sacdev_reviewed_at)->format('M d, Y — h:i A') }}
            </div>
        @endif

    @endif



    {{-- RETURNED --}}
    @if($registration->status === 'returned')

        <div class="mt-5 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-rose-900">

            <div class="font-semibold">
                Returned to Organization
            </div>

            <div class="text-sm mt-1">
                Waiting for corrections and resubmission.
            </div>

        </div>

        @if($registration->sacdev_reviewed_at)
            <div class="mt-3 text-xs text-slate-500">
                Returned on {{ \Carbon\Carbon::parse($registration->sacdev_reviewed_at)->format('M d, Y — h:i A') }}
            </div>
        @endif

    @endif



    {{-- ONLY SHOW ACTIONS IF SUBMITTED --}}
    @if($registration->status === 'submitted_to_sacdev')

        <div class="mt-6 grid grid-cols-1 xl:grid-cols-2 gap-6">


            {{-- RETURN --}}
            <form method="POST"
                  action="{{ route('admin.b2.president.return', $registration) }}"
                  class="rounded-xl border border-amber-200 bg-amber-50 p-5">

                @csrf

                <div class="font-semibold text-amber-900">
                    Return to Organization
                </div>

                <p class="text-sm text-amber-800 mt-1">
                    Send back for corrections. Remarks are required.
                </p>


                <textarea name="sacdev_remarks"
                          rows="4"
                          required
                          class="mt-3 w-full rounded-lg border border-amber-300 px-3 py-2 text-sm focus:ring-2 focus:ring-amber-200"
                          placeholder="Explain what needs correction...">{{ old('sacdev_remarks') }}</textarea>


                @error('sacdev_remarks')
                    <div class="mt-2 text-sm text-rose-600">
                        {{ $message }}
                    </div>
                @enderror


                <button type="submit"
                        class="mt-4 inline-flex items-center rounded-lg bg-amber-600 px-4 py-2 text-sm font-semibold text-white hover:bg-amber-700">
                    Return Submission
                </button>

            </form>



            {{-- APPROVE --}}
            <form method="POST"
                  action="{{ route('admin.b2.president.approve', $registration) }}"
                  class="rounded-xl border border-emerald-200 bg-emerald-50 p-5">

                @csrf

                <div class="font-semibold text-emerald-900">
                    Approve Submission
                </div>

                <p class="text-sm text-emerald-800 mt-1">
                    Confirm this submission as valid and complete.
                </p>


                <button type="submit"
                        class="mt-4 inline-flex items-center rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">
                    Approve Submission
                </button>

            </form>


        </div>

    @endif


</div>