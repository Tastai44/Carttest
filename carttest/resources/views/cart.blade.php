@extends('layouts.app-master')

@section('content')
    @auth 
        <table>
            <thead>
                <tr>
                    <th style="width:10%">Product</th>
                    <th style="width:20%">Name</th>
                    <th style="width:10%">Price</th>
                    <th style="width:8%">Quantity</th>
                    <th style="width:22%" class="text-center">Subtotal</th>
                    <th style="width:10%"></th>
                </tr>
            </thead>
            <tbody>
            @php $total = 0 @endphp
                @foreach( $carts as $cart )
                <tr>
                    @php $total += $cart->price*$cart->quantity @endphp
                    <td>{{ $cart->id }}</td>
                    <td>{{ $cart->name }}</td>
                    <td class="inner-table">{{ $cart->price }}</td>
                    <td class="form-input">
                        <input type="number" value="{{ $cart->quantity }}" class="form-control quantity update-cart" />
                    <td data-th="Subtotal" class="text-center">{{$cart->price*$cart->quantity}}</td>
                    <td class="actions" data-th="">
                        <button class="btn btn-danger btn-sm remove-from-cart"><i class="fa fa-trash-o"></i></button>
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5" class="text-right"><h3><strong>Total  {{ $total }}<h3><strong></td>

                </tr>
                <tr>
                    <td colspan="5" class="text-right">
                        <a href="{{ url('/') }}" class="btn btn-warning"><i class="fa fa-angle-left"></i> Continue Shopping</a>
                        <button class="btn btn-success">Checkout</button>
                    </td>
                </tr>
            </tfoot>
        </table>
        @endsection
        
        @section('scripts')
        <script type="text/javascript">
        
            $(".update-cart").change(function (e) {
                e.preventDefault();
        
                var ele = $(this);
        
                $.ajax({
                    url: '{{ route('update.cart') }}',
                    method: "patch",
                    data: {
                        _token: '{{ csrf_token() }}', 
                        id: ele.parents("tr").attr("data-id"), 
                        quantity: ele.parents("tr").find(".quantity").val()
                    },
                    success: function (response) {
                    window.location.reload();
                    }
                });
            });
        
            $(".remove-from-cart").click(function (e) {
                e.preventDefault();
        
                var ele = $(this);
        
                if(confirm("Are you sure want to remove?")) {
                    $.ajax({
                        url: '{{ route('remove.from.cart') }}',
                        method: "DELETE",
                        data: {
                            _token: '{{ csrf_token() }}', 
                            id: ele.parents("tr").attr("data-id")
                        },
                        success: function (response) {
                            window.location.reload();
                        }
                    });
                }
            });
        
        </script>
    @endauth
    @guest
        <h1>Homepage</h1> <br>
        <p class="lead">You don't have permission to view cart.</p>
    @endguest
@endsection
    
