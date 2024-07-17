@extends('layouts.dashboard')

@section('content')
<div class="card mt-4">
    <div class="card-header">
        <h4 class="fw-semibold m-0"> Monitoring Bimbingan Skripsi</h4>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table datatable">
                <thead>
                    <tr>
                        <th>NIM</th>
                        <th>Nama</th>
                        <th>Judul Skripsi</th>
                        <th>Keterangan</th>
                        <th>Jumlah Bimbingan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($mahasiswaBimbingan as $item)
                    <tr>
                        <td>{{ $item->nim }}</td>
                        <td>{{ $item->nama }}</td>
                        <td>{{ $item->judulSkripsi->judul_skripsi ?? '-'  }}</td>
                        <td>{{ $item->keterangan ?? '-'}}</td>
                        <td>{{ $item->bimbinganSkripsi->count() }}</td>
                        <td>
                            <button class="btn btn-primary edit-keterangan" data-id="{{ $item->id }}" data-keterangan="{{ $item->keterangan }}">Edit Keterangan</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Edit Keterangan -->
<div class="modal fade" id="editKeteranganModal" tabindex="-1" aria-labelledby="editKeteranganModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="editKeteranganForm">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editKeteranganModalLabel">Edit Keterangan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="editKeterangan" class="form-label">Keterangan</label>
                        <select class="form-select" id="editKeterangan" name="keterangan" required>
                            <option value="disetujui bab 1">Disetujui Bab 1</option>
                            <option value="revisi bab 1">Revisi Bab 1</option>
                            <option value="disetujui bab 2">Disetujui Bab 2</option>
                            <option value="revisi bab 2">Revisi Bab 2</option>
                            <option value="disetujui bab 3">Disetujui Bab 3</option>
                            <option value="revisi bab 3">Revisi Bab 3</option>
                            <option value="disetujui bab 4">Disetujui Bab 4</option>
                            <option value="revisi bab 4">Revisi Bab 4</option>
                            <option value="disetujui bab 5">Disetujui Bab 5</option>
                            <option value="revisi bab 5">Revisi Bab 5</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('.edit-keterangan').click(function() {
            var mahasiswaId = $(this).data('id');
            var keterangan = $(this).data('keterangan');
            $('#editKeterangan').val(keterangan);

            $('#editKeteranganForm').attr('action', '/bimbingan/' + mahasiswaId);
            var modal = new bootstrap.Modal(document.getElementById('editKeteranganModal'), {});
            modal.show();
        });

        $('#editKeteranganForm').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();

            $.ajax({
                url: $(this).attr('action'),
                method: 'POST', // Ubah method sesuai kebutuhan Anda
                data: formData,
                success: function(response) {
                    // Handle jika berhasil disimpan
                    console.log(response);
                    // Tutup modal
                    var modal = bootstrap.Modal.getInstance(document.getElementById('editKeteranganModal'));
                    modal.hide();
                    // Refresh halaman atau perbarui tampilan keterangan di tabel jika diperlukan
                    location.reload(); // Contoh reload halaman, bisa disesuaikan dengan kebutuhan
                },
                error: function(error) {
                    // Handle jika terjadi error
                    console.error(error);
                    alert('Gagal menyimpan keterangan.');
                }
            });
        });
    });
</script>
@endsection
