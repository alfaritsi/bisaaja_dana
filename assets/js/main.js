$(document).ready(function() {
    // $('#simulasi').hide(); // disembunyikan saat load awal
});
//get type
$('#jenis_kendaraan').change(function() {
    var jenis_kendaraan = $(this).val();
    if (jenis_kendaraan) {
        $.ajax({
            url: base_url+'main/get_merk_by_category/' + jenis_kendaraan,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                $('#merk_kendaraan').empty();
                $('#merk_kendaraan').append('<option value="">Pilih Merk Kendaraan</option>');
                $.each(data.data, function(index, item) {
                    $('#merk_kendaraan').append('<option value="'+ item.brandid +'">'+ item.brandname +'</option>');
                });
            },
            error: function() {
                alert('Gagal mengambil data merk kendaraan.');
            }
        });
    } else {
        $('#merk_kendaraan').html('<option value="">Pilih Jenis Kendaraan Terlebih Dahulu</option>');
    }
});

//get merk
$('#merk_kendaraan').change(function() {
  var merk_kendaraan = $(this).val();
  var jenis_kendaraan = $('#jenis_kendaraan').val(); // Ambil nilai jenis_kendaraan
  if (merk_kendaraan) {
      $.ajax({
          url: base_url + 'main/get_type_by_merk/' + merk_kendaraan + '?jenis_kendaraan=' + jenis_kendaraan,
          type: 'GET',
          dataType: 'json',
          success: function(data) {
              $('#tipe_kendaraan').empty();
              $('#tipe_kendaraan').append('<option value="">Pilih Tipe Kendaraan</option>');
              $.each(data.data, function(index, item) {
                  $('#tipe_kendaraan').append('<option value="'+ item.modelid +'">'+ item.modelname +'</option>');
              });
          },
          error: function() {
              alert('Gagal mengambil data tipe kendaraan.');
          }
      });
  } else {
      $('#tipe_kendaraan').html('<option value="">Pilih Merk Kendaraan Terlebih Dahulu</option>');
  }
});

//get year
$('#tipe_kendaraan').change(function() {
  var tipe_kendaraan = $(this).val();
  var jenis_kendaraan = $('#jenis_kendaraan').val(); // Ambil nilai jenis_kendaraan
  if (tipe_kendaraan) {
      $.ajax({
          url: base_url + 'main/get_tahun/' + tipe_kendaraan + '?jenis_kendaraan=' + jenis_kendaraan,
          type: 'GET',
          dataType: 'json',
          success: function(data) {
              $('#tahun_kendaraan').empty();
              $('#tahun_kendaraan').append('<option value="">Pilih Tahun Kendaraan</option>');
              $.each(data.data, function(index, item) {
                  $('#tahun_kendaraan').append('<option value="'+ item.availableyear +'">'+ item.availableyear +'</option>');
              });
          },
          error: function() {
              alert('Gagal mengambil data tipe kendaraan.');
          }
      });
  } else {
      $('#tahun_kendaraan').html('<option value="">Pilih Tipe Kendaraan Terlebih Dahulu</option>');
  }
});
//get max
$('#tahun_kendaraan').change(function() {
  var tahun_kendaraan = $(this).val();
  var jenis_kendaraan = $('#jenis_kendaraan').val();
  var tipe_kendaraan = $('#tipe_kendaraan').val();
  var region_kendaraan = $('#region_kendaraan').val();

  if (tipe_kendaraan) {
        $.ajax({
            url: base_url + 'main/get_max/' + tahun_kendaraan + 
                '?jenis_kendaraan=' + jenis_kendaraan +
                '&tipe_kendaraan=' + tipe_kendaraan +
                '&region_kendaraan=' + region_kendaraan,
            type: 'GET',
            dataType: 'json',

            success: function(response) {
                if (response.success && response.data.length > 0) {
                    var harga = response.data[0].D_HARGA_OTR;
                    var max_pinjam = response.data[0].D_MAKSIMUM_PENCAIRAN;
                    $('#harga_kendaraan').val(harga.toLocaleString('id-ID')).prop('disabled', true);
                    $('#max_pinjam').val(max_pinjam.toLocaleString('id-ID')).prop('disabled', true);
                    $('#input_pinjam').val(max_pinjam.toLocaleString('id-ID'));
                } else {
                    $('#harga_kendaraan').val('');
                    $('#max_pinjam').val('');
                    $('#input_pinjam').val('');
                    alert('Estimasi harga tidak ditemukan.');
                }
            },            
            error: function() {
                // alert('Gagal mengambil data estimasi harga.');
                alert(url);
            }
        });
    } else {
        $('#harga_kendaraan').val('');
        $('#max_pinjam').val('');
        $('#input_pinjam').val('');
    }
});


$('.btn_back_simulasi').click(function() {
    $('#simulasi').attr('hidden', true);
    $('#input').removeAttr('hidden');
});
$('.btn_back_tenor').click(function() {
    $('#input_data').attr('hidden', true);
    $('#simulasi').removeAttr('hidden');
});

//get max
$('.btn_simulasi').click(function() {
    var jenis_kendaraan = $('#jenis_kendaraan').val();
    var tipe_kendaraan = $('#tipe_kendaraan').val();
    var merk_kendaraan = $('#merk_kendaraan').val();
    var region_kendaraan = $('#region_kendaraan').val();
    var tahun_kendaraan = $('#tahun_kendaraan').val();
    var harga_kendaraan = $('#harga_kendaraan').val();
    var input_pinjam = $('#input_pinjam').val();
    // Validasi
    if (region_kendaraan=='') {
        alert('Area tinggal wajib diisi.');
        $('#region_kendaraan').focus();
        return;
    }
    if (jenis_kendaraan=='') {
        alert('Jenis kendaraan wajib diisi.');
        $('#jenis_kendaraan').focus();
        return;
    }
    if (merk_kendaraan=='') {
        alert('Merk kendaraan wajib diisi.');
        $('#tipe_kendaraan').focus();
        return;
    }
    if (tipe_kendaraan=='') {
        alert('Tipe kendaraan wajib diisi.');
        $('#tipe_kendaraan').focus();
        return;
    }
    if (tahun_kendaraan=='') {
        alert('Tahun kendaraan wajib diisi.');
        $('#tahun_kendaraan').focus();
        return;
    }
    if (!harga_kendaraan) {
        alert('Harga kendaraan wajib diisi.');
        $('#harga_kendaraan').focus();
        return;
    }
    if (!input_pinjam) {
        alert('Rencana pencairan wajib diisi.');
        $('#input_pinjam').focus();
        return;
    }
    // Jika semua valid, tampilkan hasil simulasi
    $('#input').attr('hidden', true);
    $('#simulasi').removeAttr('hidden').addClass('animate__animated animate__fadeInRight');

    $.ajax({
        url: base_url + 'main/get_simulasi' + 
            '?jenis_kendaraan=' + jenis_kendaraan +
            '&tipe_kendaraan=' + tipe_kendaraan +
            '&region_kendaraan=' + region_kendaraan +
            '&tahun_kendaraan=' + tahun_kendaraan +
            '&harga_kendaraan=' + parseInt(harga_kendaraan.replace(/[^0-9]/g, '')) +
            '&input_pinjam=' + parseInt(input_pinjam.replace(/[^0-9]/g, '')),
        type: 'GET',
        dataType: 'json',

        success: function (response) {
            if (response.success) {
                // Tampilkan section simulasi
                // $('#simulasi').slideDown();

                // Ambil data cicilan dan pencairan
                var pencairan = response.vehicledata.D_PENCAIRAN;
                var data_cicilan = response.installmentdata;

                // Kosongkan tbody terlebih dahulu
                var tbody = '';
                $.each(data_cicilan, function (index, item) {
                    tbody += '<tr>' +
                        '<td>' + item.D_TENOR + ' Bulan</td>' +
                        '<td>' + Number(pencairan).toLocaleString('id-ID') + '</td>' +
                        '<td>' + Number(item.D_ANGSURAN).toLocaleString('id-ID') + '</td>' +
                        '<td><a href="#input_data" class="btn_tenor btn btn-orange-red tra-orange-red-hover mr-15" data-tenor="' + item.D_TENOR + '" data-angsuran="' + item.D_ANGSURAN + '">Pilih</a></td>' +
                        '</tr>';
                });
                
                // Masukkan ke dalam <tbody>
                $('.table-responsive table tbody').html(tbody);
                //buat isi detail
                $('#area_kend').text(response.vehicledata.D_AREA);
                $('#jenis_kend').text(response.vehicledata.D_JENIS_KENDARAAN);
                $('#model_kend').text(response.vehicledata.D_MODEL_NAME+' - '+response.vehicledata.D_MODEL);
                $('#tahun_kend').text(response.vehicledata.D_TAHUN);
                $('#harga_kend').text(response.vehicledata.D_HARGA_OTR.toLocaleString('id-ID'));
                $('#max_kend').text(response.vehicledata.D_MAKSIMUM_PENCAIRAN.toLocaleString('id-ID'));
                $('#dana_kend').text(response.vehicledata.D_PENCAIRAN.toLocaleString('id-ID'));
            } else {
                alert('Data simulasi tidak tersedia.');
            }
        },
        error: function() {
            // alert('Gagal mengambil data estimasi harga.');
            alert(url);
        }
    });
});

$(document).on('click', '.btn_tenor', function (e) {
    e.preventDefault();
    var tenor = $(this).data('tenor');
    var angsuran = $(this).data('angsuran');
    $('#tenor_kend').text(tenor);
    $('#angsuran').text(angsuran.toLocaleString('id-ID'));


});
$(document).on('click', '.btn_input', function (e) {
    $('#simulasi').attr('hidden', true);
    $('#input_data').attr('hidden', true);
    $('#input').removeAttr('hidden');
});
$(document).on('click', '.btn_input_data', function (e) {
    var tenorVal = $('#tenor_kend').text().trim();

    if (tenorVal === '-' || tenorVal === '') {
        e.preventDefault(); // hentikan aksi pindah halaman / submit
        alert('Tenor wajib dipilih terlebih dahulu.');
        return;
    }
    $('#simulasi').attr('hidden', true);
    $('#input_data').removeAttr('hidden');


});
$(document).on('click', '.btn_save', function (e) {
    e.preventDefault(); // Mencegah aksi default link
    var isCek1 = $('#cek1').is(':checked');
    if (!isCek1) {
        alert('Anda harus menyetujui kedua pernyataan sebelum mengajukan pinjaman.');
        return;
    }

    //data simulasi
    var jenis_kendaraan = $('#jenis_kendaraan').val();
    var tipe_kendaraan = $('#tipe_kendaraan').val();
    var merk_kendaraan = $('#merk_kendaraan').val();
    var region_kendaraan = $('#region_kendaraan').val();
    var tahun_kendaraan = $('#tahun_kendaraan').val();
    var harga_kendaraan = $('#harga_kendaraan').val();
    var harga_kendaraan = parseInt(harga_kendaraan.replace(/[^0-9]/g, ''));
    var max_pinjam = $('#max_pinjam').val();
    var max_pinjam = parseInt(max_pinjam.replace(/[^0-9]/g, ''));
    var input_pinjam = $('#input_pinjam').val();
    var input_pinjam = parseInt(input_pinjam.replace(/[^0-9]/g, ''));
    var tenor_kend = $('#tenor_kend').text().trim();
    var angsuran = $('#angsuran').text().trim();
    var angsuran = parseInt(angsuran.replace(/[^0-9]/g, ''));
    //data user
    var name = $('[name="name"]').val();
    var nomor = $('[name="nomor"]').val();
    var email = $('[name="email"]').val();
    var nomor_ktp = $('[name="nomor_ktp"]').val();
    var dob = $('[name="dob"]').val();
    var address = $('[name="address"]').val();
    
    $.ajax({
        url: base_url + 'main/save_data',
        method: 'POST',
        data: {
            jenis_kendaraan: jenis_kendaraan,
            tipe_kendaraan: tipe_kendaraan,
            merk_kendaraan: merk_kendaraan,
            region_kendaraan: region_kendaraan,
            tahun_kendaraan: tahun_kendaraan,
            harga_kendaraan: harga_kendaraan,
            max_pinjam: max_pinjam,
            input_pinjam: input_pinjam,
            tenor_kend: tenor_kend,
            angsuran: angsuran,
            name: name,
            nomor: nomor,
            email: email,
            nomor_ktp: nomor_ktp,
            dob: dob,
            address: address
        },
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                alert('Data berhasil disimpan!');
                window.location.href = base_url;
            } else {
                alert('Gagal menyimpan data: ' + response.message);
            }
        },
        error: function (xhr, status, error) {
            alert('Terjadi kesalahan: ' + error);
        }
    });

});
  