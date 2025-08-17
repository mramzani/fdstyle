<div class="offcanvas-header">
    <h5 id="offcanvasStartLabel" class="offcanvas-title">تاریخچه موجودی کالا</h5>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
</div>
<div class="offcanvas-body mx-0 flex-grow-0">
    @if($histories->count() == 0)
        <div class="alert alert-warning">برای این محصول تاریخچه‌ای ثبت نشده است.</div>
    @else
        <div class="card shadow-none bg-transparent">
            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>تنوع</th>
                        <th>توضیحات</th>
                        <th>توسط</th>
                        <th>تاریخ</th>
                    </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                    @foreach($histories as $history)
                        @php
                            $description = "با ";

                        if ($history->action_type == "add"){
                            $description .= "افزودن ";
                        }elseif($history->action_type == "delete"){
                            $description .= "حذف ";
                        }elseif($history->action_type == "add_add"){
                            $description .= "افزودن - افزایش ";
                        }elseif($history->action_type == "add_subtract"){
                            $description .= "افزودن - کاهش ";
                        }elseif($history->action_type == "delete_subtract"){
                            $description .= "حذف - کاهش ";
                        }elseif($history->action_type == "delete_add"){
                            $description .= "حذف - افزایش ";
                        }

                        if($history->order_type == "purchases"){
                            $description .= "خرید تعداد " . $history->quantity . " عدد ";
                        } elseif($history->order_type == "sales") {
                            $description .= "فروش تعداد " . $history->quantity . " عدد ";
                        } elseif($history->order_type == "stock_adjustment"){
                           $description .= "تنظیم‌موجودی تعداد " . $history->quantity . " عدد ";
                        } elseif($history->order_type == "online"){
                           $description .= "خریدآنلاین تعداد " . $history->quantity . " عدد ";
                        }

                        if ($history->stock_type == "in"){
                            $description .= "افزایش یافت";
                        }elseif ($history->stock_type == "out"){
                            $description .= "کاهش یافت";
                        }


                        @endphp
                        <tr>
                            <td>{{ $loop->index + 1 }}</td>
                            <td>{{ $history->variant != null ? $history->variant->option->valuable->title : 'ندارد' }}</td>
                            <td>{{ $description }}</td>
                            <td>{{ $history->staff->full_name ?? '' }}</td>
                            <td>{{ verta($history->created_at)->format('j %B Y - H:i') }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
