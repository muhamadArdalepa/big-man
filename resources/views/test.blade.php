<!DOCTYPE html>
<html>
<head>
  <title>DataTable Collapsed Data</title>
  <link href="https://cdn.datatables.net/1.11.0/css/dataTables.bootstrap5.min.css" rel="stylesheet">
</head>
<body>
  <div class="container mt-4">
    <table id="collapsedDataTable" class="table table-bordered">
      <thead>
        <tr>
          <th>Column 1</th>
          <th>Column 2</th>
          <th>Column 3</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Row 1 Data 1</td>
          <td>Row 1 Data 2</td>
          <td>Row 1 Data 3</td>
        </tr>
        <tr>
          <td>Row 2 Data 1</td>
          <td>Row 2 Data 2</td>
          <td>Row 2 Data 3</td>
        </tr>
        <!-- Add more rows as needed -->
      </tbody>
    </table>
  </div>

  <!-- Include jQuery, DataTables, and Bootstrap JS files -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.datatables.net/1.11.0/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.11.0/js/dataTables.bootstrap5.min.js"></script>
  <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>

  <script>
    $(document).ready(function() {
      // Inisialisasi DataTables
      $('#collapsedDataTable').DataTable({
        paging: false, // Nonaktifkan pagination
        info: false, // Nonaktifkan info table
        searching: false, // Nonaktifkan fitur searching
        responsive: true, // Aktifkan responsivitas tabel
        columnDefs: [
          { className: 'control', orderable: false, targets: 0 } // Memberikan class untuk ikon toggle dan nonaktifkan pengurutan untuk kolom ikon toggle
        ]
      });
    });
  </script>
</body>
</html>
