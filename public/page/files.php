
<h1 class="text-3xl font-bold text-clifford">
  Files
</h1>

<p>Hello <?php echo $app['user']->username; ?>.</p>

<form action="/" enctype="multipart/form-data" id="add-file-form" name="file-upload" method="POST">
  <label class="block text-md font-semibold text-slate-700" for="file">Add a file</label>
  <input class="form-input placeholder:text-slate-400 peer px-0 py-2 border-0" id="file-input" name="file" placeholder="File" required type="file" />
  <button class="text-slate-800 px-2 border border-slate-600 mr-4 rounded-md" type="submit">Upload</button>
  <input type="hidden" name="form-name" value="files-form"/>
</form>

<span class="block mt-3 text-md font-semibold text-slate-700">
  Your files - page <span id="page"></span> of  <span id="pages"></span>, total <span id="count-all"></span>
</span>

<table class="table-auto mt-0">
  <thead>
    <tr>
      <th>Name</th>
      <th>Type</th>
      <th>Upload Date</th>
      <th></th>
    </tr>
  </thead>
  <tbody id="table-files">
  </tbody>
</table>

<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

<script>

var TOKEN;

$(document).ready(function() {

  TOKEN = getToken()

  $('#add-file-form').submit(function (e) {
    e.preventDefault()
    addFile()
  })

  getFiles()
})

function addFile() {
  let file = document.getElementById('file-input').files[0]
  let reader = new FileReader()

  reader.onload = (e) => {
    $.post(
      'files-api.php',
      {
        name: file.name,
        data: e.target.result,
        size: file.size,
        type: file.type,
        token: TOKEN,
      },
      function (data) {
        TOKEN = data.token
        renderTable(data)
      },
      'json'
    )
  }

  reader.readAsDataURL(file)
}

function getFiles() {
  $.getJSON(
    'files-api.php',
    { page: 1, token: TOKEN },
    function(data) {
      TOKEN = data.token
      renderTable(data)
    }
  )
}

function getToken() {
  let cookie = RegExp("token=[^;]+").exec(document.cookie);
  let token = decodeURIComponent(!!cookie ? cookie.toString().replace(/^[^=]+./,"") : "")

  return token
}

function renderTable(data) {
  $('#count').empty().append(data.count)
  $('#count-all').empty().append(data.countAll)
  $('#page').empty().append(Math.ceil(data.page));
  $('#pages').empty().append(Math.ceil(data.countAll / 5));
  $('#table-files').empty();

  $.each(data.rows, function(key, value) {
    let fileRow = ''

    fileRow += '<td>' + value.original_name + '</td>'
    fileRow += '<td>' + value.type + '</td>'
    fileRow += '<td>' + value.created_at + '</td>'
    fileRow += '<td><button class="text-slate-800 px-2 border border-slate-600 rounded-md" id="file-view-button-' + key + '" data-file="' + value.id + '" type="button">Show</button></td>'

    $('#table-files').append('<tr>' + fileRow + '</tr>')
  })

  for (let i = data.count; i < 5; i++) {
    $('#table-files').append('<tr><td colspan="4">&nbsp;</td></tr>')
  }

  $('[id^=file-view-button-').on(
    'click',
    function (e) {
      e.preventDefault()
      window.open('files-api.php?token=' + TOKEN + '&id=' + $(this).data('file'), '_blank');
    }
  )
}

</script>
