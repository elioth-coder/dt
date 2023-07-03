<div class="p-3">
    <div class="alert alert-primary alert-dismissible fade show" role="alert">
        <strong>Howdy, 
            <img style="height: 30px; margin-top: -10px;" 
                src="<?php echo ($page=="dashboard") ? "" : "." ?>./upload/<?php echo $_SESSION['user']['profile']; ?>" 
                class="rounded-circle border shadow" 
            />
            @<?php echo $_SESSION['user']['username']; ?>!</strong> have a great day!
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
</div>