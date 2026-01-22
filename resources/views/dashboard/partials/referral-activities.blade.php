@if(isset($referralActivitiesData) && $referralActivitiesData['total'] > 0)
    <div class="mining-activity-list">
        @foreach($referralActivitiesData['data'] as $activity)
            @php
                $date = \Carbon\Carbon::parse($activity['created_at']);
                $dateFormatted = $date->format('M d, Y');
                $timeFormatted = $date->format('g:i A');
                $dateTimeFormatted = $dateFormatted . ', ' . $timeFormatted;
                $amountFormatted = '+' . number_format($activity['amount'], 6, '.', '');
                $walletBalanceFormatted = number_format($activity['referral_wallet_balance'], 6, '.', '');
            @endphp
            <div class="mining-activity-item" 
                 data-activity='@json($activity)'
                 style="cursor: pointer;">
                <div class="mining-activity-icon">
                    <i class="fas fa-check"></i>
                </div>
                <div class="mining-activity-content-wrapper">
                    <div class="mining-activity-type">{{ $activity['type_label'] }}</div>
                    <div class="mining-activity-date">{{ $dateTimeFormatted }}</div>
                    <div class="mining-activity-status">completed</div>
                </div>
                <div class="mining-activity-amount-wrapper">
                    <div class="mining-activity-amount">{{ $amountFormatted }}</div>
                    <div class="mining-activity-wallet-balance">Referral Wallet: ${{ $walletBalanceFormatted }}</div>
                </div>
            </div>
        @endforeach
    </div>

    @if($referralActivitiesData['last_page'] > 1)
        <div class="mining-activity-pagination">
            @php
                $currentPage = $referralActivitiesData['current_page'];
                $lastPage = $referralActivitiesData['last_page'];
            @endphp
            
            @if($currentPage > 1)
                <a href="#" data-page="{{ $currentPage - 1 }}" class="pagination-btn pagination-prev">
                    <i class="fas fa-chevron-left"></i>
                </a>
            @else
                <span class="pagination-btn pagination-prev disabled">
                    <i class="fas fa-chevron-left"></i>
                </span>
            @endif

            @php
                $startPage = max(1, $currentPage - 2);
                $endPage = min($lastPage, $currentPage + 2);
                
                // Show first page if not in range
                if ($startPage > 1) {
                    $endPage = min($lastPage, $startPage + 4);
                }
                
                // Show last page if not in range
                if ($endPage < $lastPage) {
                    $startPage = max(1, $endPage - 4);
                }
            @endphp

            @if($startPage > 1)
                <a href="#" data-page="1" class="pagination-number">1</a>
                @if($startPage > 2)
                    <span class="pagination-ellipsis">...</span>
                @endif
            @endif

            @for($page = $startPage; $page <= $endPage; $page++)
                @if($page == $currentPage)
                    <span class="pagination-number active">{{ $page }}</span>
                @else
                    <a href="#" data-page="{{ $page }}" class="pagination-number">{{ $page }}</a>
                @endif
            @endfor

            @if($endPage < $lastPage)
                @if($endPage < $lastPage - 1)
                    <span class="pagination-ellipsis">...</span>
                @endif
                <a href="#" data-page="{{ $lastPage }}" class="pagination-number">{{ $lastPage }}</a>
            @endif

            @if($currentPage < $lastPage)
                <a href="#" data-page="{{ $currentPage + 1 }}" class="pagination-btn pagination-next">
                    <i class="fas fa-chevron-right"></i>
                </a>
            @else
                <span class="pagination-btn pagination-next disabled">
                    <i class="fas fa-chevron-right"></i>
                </span>
            @endif
        </div>
    @endif
@else
    <div class="mining-empty-state">
        <div class="mining-empty-icon">
            <i class="fas fa-chart-area"></i>
        </div>
        <p class="mining-empty-text">No referral activity yet. Invite users to earn commissions.</p>
    </div>
@endif

