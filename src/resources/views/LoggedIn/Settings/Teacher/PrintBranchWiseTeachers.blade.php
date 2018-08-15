<table width="100%" border="1" style="font-size: 12px; border-collapse: collapse;">
    <thead>
        <tr>
            <th>Sl</th>
            <th>Teacher ID</th>
            <th>Full Name</th>
            <th>Email</th>
            <th>Contact No.</th>
        </tr>
    </thead>

    <tbody>
        @if(isset($Teachers))
        <?php $sl = 1; ?>
        @foreach($Teachers as $T)
        <tr>
            <td><?php echo $sl++; ?></td>
            <td>{{ $T->username }}</td>
            <td>{{ $T->full_name }}</td>
            <td>{{ $T->email }}</td>
            <td>{{ $T->phone_no }}</td>
        </tr>
        @endforeach
        @endif
    </tbody>

</table>