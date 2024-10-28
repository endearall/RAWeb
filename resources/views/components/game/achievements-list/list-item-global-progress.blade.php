@props([
    'achievement' => [],
    'totalPlayerCount' => 1,
])

<?php
$wonBy = 0;
$wonByHardcore = 0;
$unlockRate = 0;
$hardcoreUnlockRate = 0;
$hardcoreProgressBarWidth = 0;
$softcoreProgressBarWidth = 0;

if ($totalPlayerCount > 0) {
    // NOTE: Because we're currently including Untracked players when the player count
    // for the game is >100, it's possible for various unlock rates to be greater than 100%.
    $wonBy = isset($achievement['NumAwarded']) ? $achievement['NumAwarded'] : 0;
    $wonByHardcore = isset($achievement['NumAwardedHardcore']) ? $achievement['NumAwardedHardcore'] : 0;
    if ($wonBy > $totalPlayerCount) {
        $wonBy = $totalPlayerCount;
    }
    if ($wonByHardcore > $totalPlayerCount) {
        $wonByHardcore = $totalPlayerCount;
    }

    $unlockRate = sprintf("%01.2f", ($wonBy / $totalPlayerCount) * 100);
    $hardcoreUnlockRate = sprintf("%01.2f", ($wonByHardcore / $totalPlayerCount) * 100);
    $hardcoreProgressBarWidth = $hardcoreUnlockRate;
    $softcoreProgressBarWidth = $unlockRate - $hardcoreProgressBarWidth;
}
?>


<p class="text-2xs text-center hidden md:block -mt-1.5">
    @if ($wonByHardcore > 0 && $wonBy > $wonByHardcore)
        <span title="Hardcore unlock rate" class="font-bold cursor-help">{{ $hardcoreUnlockRate }}%</span>
        <span title="Total unlock rate" class="cursor-help">({{ $unlockRate }}%)</span>
    @else
        <span title="Total unlock rate" class="{{ $wonByHardcore > 0 ? 'font-bold' : '' }} cursor-help">{{ $unlockRate }}%</span>
    @endif
    unlock rate
</p>

<p id="progress-label-{{ $achievement['ID'] }}" class="mb-0.5 text-2xs md:text-center md:mb-0">
    @if ($wonByHardcore > 0 && $wonBy > $wonByHardcore)
        <span title="Hardcore unlocks" class="font-bold cursor-help">{{ localized_number($wonByHardcore) }}</span>
        <span title="Total unlocks" class="cursor-help">({{ localized_number($wonBy) }})</span>
    @else
        <span title="Total unlocks" class="{{ $wonByHardcore > 0 ? 'font-bold' : '' }} cursor-help">{{ localized_number($wonBy) }}</span>
    @endif
    of 
    <span title="Total players" class="cursor-help">{{ localized_number($totalPlayerCount) }}</span>
    <span class="md:hidden">–</span>
    @if ($wonByHardcore > 0 && $wonBy > $wonByHardcore)
        <span title="Hardcore unlock rate" class="font-bold cursor-help md:hidden">{{ $hardcoreUnlockRate }}%</span>
        <span title="Total unlock rate" class="cursor-help md:hidden">({{ $unlockRate }})%</span>
    @else
        <span title="Total unlock rate" class="{{ $wonByHardcore > 0 ? 'font-bold' : '' }} cursor-help md:hidden">{{ $unlockRate }}%</span>
    @endif
    <span class="hidden sm:inline md:hidden">unlock rate</span>
</p>

<div
    role="progressbar"
    aria-valuemin="0"
    aria-valuemax="100"
    aria-valuenow="{{ $unlockRate }}"
    aria-labelledby="progress-label-{{ $achievement['ID'] }}"
    class="w-full h-1 bg-zinc-950 light:bg-zinc-300 rounded flex space-x-px overflow-hidden"
>
    {{-- Hardcore completion --}}
    <div
        style="width: {{ $hardcoreProgressBarWidth }}%"
        class="bg-gradient-to-r from-amber-500 to-[gold] light:bg-yellow-500 h-full"
    >
        <span class="sr-only">
            {{ $hardcoreProgressBarWidth }}% of players have earned the achievement in hardcore mode
        </span>
    </div>

    {{-- Softcore completion --}}
    <div
        style="width: {{ $softcoreProgressBarWidth }}%"
        class="bg-neutral-500 h-full"
    >
        <span class="sr-only">
            {{ $unlockRate }}% of players have earned the achievement in either hardcore or softcore mode
        </span>
    </div>
</div>
