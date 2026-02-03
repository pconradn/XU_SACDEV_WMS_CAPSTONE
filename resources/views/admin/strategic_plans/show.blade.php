<x-app-layout>
    <div class="space-y-6"
         x-data="{ openReturn:false, openRevert:false, openApprove:false }">

        @include('admin.strategic_plans._header', [
            'submission' => $submission
        ])

        @include('admin.strategic_plans._remarks', [
            'submission' => $submission
        ])

        @include('admin.strategic_plans._summary', [
            'submission' => $submission
        ])

        @include('admin.strategic_plans._timeline', [
            'submission' => $submission
        ])

        @include('admin.strategic_plans._identity', [
            'submission' => $submission
        ])

        @include('admin.strategic_plans._projects', [
            'submission' => $submission
        ])

        @include('admin.strategic_plans._funds', [
            'submission' => $submission
        ])

        @include('admin.strategic_plans._actions', [
            'submission' => $submission
        ])

        {{-- APPROVE MODAL --}}
        <div x-show="openApprove" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-slate-900/50" @click="openApprove=false"></div>
            <div class="relative w-full max-w-xl rounded-2xl bg-white border border-slate-200 shadow-xl p-5">
                <h3 class="text-lg font-semibold text-slate-900">Approve Strategic Plan</h3>
                <p class="text-sm text-slate-600 mt-1">Confirm approval. This will lock edits (unless reverted later).</p>

                <form class="mt-4 space-y-3"
                      method="POST"
                      action="{{ route('admin.strategic_plans.approve', $submission) }}">
                    @csrf
                    <textarea name="remarks" rows="4"
                              class="w-full rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500"
                              placeholder="(optional) Approval note..."></textarea>

                    <div class="flex justify-end gap-2">
                        <button type="button"
                                class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700"
                                @click="openApprove=false">
                            Cancel
                        </button>
                        <button type="submit"
                                class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">
                            Confirm Approve
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- RETURN MODAL --}}
        <div x-show="openReturn" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-slate-900/50" @click="openReturn=false"></div>
            <div class="relative w-full max-w-xl rounded-2xl bg-white border border-slate-200 shadow-xl p-5">
                <h3 class="text-lg font-semibold text-slate-900">Return to Organization</h3>
                <p class="text-sm text-slate-600 mt-1">
                    This will allow the president to edit again. Remarks are required.
                </p>

                <form class="mt-4 space-y-3"
                      method="POST"
                      action="{{ route('admin.strategic_plans.return', $submission) }}">
                    @csrf

                    <textarea name="remarks" rows="4"
                              class="w-full rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500"
                              placeholder="Enter required changes..." required></textarea>

                    <div class="flex justify-end gap-2">
                        <button type="button"
                                class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700"
                                @click="openReturn=false">
                            Cancel
                        </button>
                        <button type="submit"
                                class="rounded-lg bg-rose-600 px-4 py-2 text-sm font-semibold text-white hover:bg-rose-700">
                            Return
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- REVERT APPROVAL MODAL --}}
        <div x-show="openRevert" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-slate-900/50" @click="openRevert=false"></div>
            <div class="relative w-full max-w-xl rounded-2xl bg-white border border-slate-200 shadow-xl p-5">
                <h3 class="text-lg font-semibold text-slate-900">Revert Approval</h3>

                <div class="mt-2 rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-900">
                    <div class="font-semibold">Effect of this action:</div>
                    <ul class="list-disc pl-5 mt-2 space-y-1">
                        <li>Status becomes <span class="font-semibold">returned_by_sacdev</span></li>
                        <li>The organization will be able to edit again</li>
                        <li>This requires a remark (audit trail)</li>
                    </ul>
                </div>

                <form class="mt-4 space-y-3"
                      method="POST"
                      action="{{ route('admin.strategic_plans.revert_approval', $submission) }}">
                    @csrf

                    <textarea name="remarks" rows="4"
                              class="w-full rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500"
                              placeholder="Why is the approval being reverted?" required></textarea>

                    <div class="flex justify-end gap-2">
                        <button type="button"
                                class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700"
                                @click="openRevert=false">
                            Cancel
                        </button>
                        <button type="submit"
                                class="rounded-lg bg-amber-600 px-4 py-2 text-sm font-semibold text-white hover:bg-amber-700">
                            Confirm Revert
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>
