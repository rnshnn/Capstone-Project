<script>
const S_MES_<?= $i; ?> = document.getElementById("S_MES_<?= $i; ?>");
const messageContent_<?= $i; ?> = S_MES_<?= $i; ?>.textContent.trim();

if (messageContent_<?= $i; ?>.toLowerCase() === 'processing request'
|| messageContent_<?= $i; ?>.toLowerCase() === 'waiting') {
    S_MES_<?= $i; ?>.style.color = "green";
} else {
    S_MES_<?= $i; ?>.style.color = "orangered";
}
</script>

<script>
function showApproveDialog(index) {
    Swal.fire({
        title: 'Approve Request',
        text: 'Are you sure you want to approve this request?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, approve it!'
    }).then((result) => {
        if (result.isConfirmed) {
            // Manually trigger the form submission
            document.getElementById('approveForm' + index).submit();
        }
    });
}
</script>


<script>
document.getElementById('update_mes<?= $i; ?>').addEventListener('change', function(event) {
    const selectedOption = event.target.options[event.target.selectedIndex].text;
    const label = document.querySelector(
        '.custom-file-label[for="update_mes<?= $i; ?>"]');
    label.textContent = selectedOption;
});

document.getElementById('submitRequestButton<?= $i; ?>').addEventListener('click', function(
    event) {
    event.preventDefault();

    const selectedOption = document.getElementById('update_mes<?= $i; ?>').value;

    if (selectedOption === 'processing request') {
        Swal.fire({
            title: 'Submit',
            text: 'Are you sure you want to submit this action?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Submit it!'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('returnForm<?= $i; ?>').submit();
            }
        });
    }
});
</script>