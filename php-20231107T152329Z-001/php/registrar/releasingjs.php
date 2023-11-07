<script>
const S_MES_<?= $i; ?> = document.getElementById("S_MES_<?= $i; ?>");
const messageContent_<?= $i; ?> = S_MES_<?= $i; ?>.textContent.trim();

if (messageContent_<?= $i; ?>.toLowerCase() === 'ready to pick up' ||
    messageContent_<?= $i; ?>.toLowerCase() === 'ready to ship') {
    S_MES_<?= $i; ?>.style.color = "green";
} else {
    S_MES_<?= $i; ?>.style.color = "orangered";
}
</script>

<script>
function showApproveDialog(index) {
    Swal.fire({
        title: 'Releasing',
        text: 'Are you sure you want to Release this request?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Release it!'
    }).then((result) => {
        if (result.isConfirmed) {
            // Manually trigger the form submission
            document.getElementById('approveForm' + index).submit();
        }
    });
}
</script>