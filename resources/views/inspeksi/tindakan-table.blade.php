<table class="table " id="table-tindakan">
    <thead>
        <tr>
            <th>No</th>
            <th>Tindakan</th>
            <th>Checklist</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($tindakans as $tindakan)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $tindakan->tindakan_nama }}</td>
                <td>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault{{ $tindakan->id }}"
                            name="tindakan_id[]" value="{{ $tindakan->id }}">
                        <label class="form-check-label" for="flexSwitchCheckDefault{{ $tindakan->id }}"></label>
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
