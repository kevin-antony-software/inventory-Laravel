@extends('dashboard')
@section('content')

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


        <div class="container pt-2">
            <form method="POST" action="{{ route('componentPurchase.update', $ComponentPurchase->id) }}">
                @csrf
                @method('PUT')
                <table id="myTable" class="display" width="100%">
                    <thead class="thead-dark">
                        <tr>
                            <th width=30%>item</th>
                            <th width=30%>Old</th>
                            <th width=30%>New</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Component ID</td>
                            <td>{{ $ComponentPurchase->component_id }}</td>
                            <td><input type="text" name="component_id" id="component_id" class="form-control" readonly>
                            </td>
                        </tr>
                        <tr>
                            <td>Component Name</td>
                            <td>{{ $ComponentPurchase->component_name }}</td>
                            <td><input type="text" name="component_name" id="component_name" class="form-control"></td>
                        </tr>
                        <tr>
                            <td>Qty</td>
                            <td>{{ $ComponentPurchase->qty }}</td>
                            <td><input type="text" name="qty" id="qty" class="form-control" autocomplete="off"
                                    value="{{ $ComponentPurchase->qty }}"></td>
                        </tr>
                    </tbody>
                </table>
                <div class="form-group pt-5">
                    <button class="btn btn-block btn-primary" type="submit">Update Purchase</button>
                </div>
            </form>

        </div>
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        
    <script type='text/javascript'>
        $(window).load(function() {

            plist = [
                    @foreach ($components as $p)
                        {label: "{!! $p->component_name !!}", productCode: "{!! $p->id !!}",
                        },
                    @endforeach
                ],

                $('#component_name').autocomplete({
                    source: plist,
                    minLength: 2,

                    select: function(event, ui) {
                        event.preventDefault();
                        $('#component_name').val(ui.item.label);
                        this.value = ui.item.label;
                        $('#component_id').val(ui.item.productCode);
                    }
                });
        });
    </script>
@endsection
