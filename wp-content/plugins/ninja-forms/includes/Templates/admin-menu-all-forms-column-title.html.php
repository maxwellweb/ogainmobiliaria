<?php if( ! $locked ): ?>
<a href="<?php echo $edit_url; ?>">
    <strong><?php echo $title; ?></strong>
</a>
<?php else: ?>
    <strong><?php echo $title; ?></strong>
<?php endif; ?>

<div class="row-actions">


    <span class="edit">
        <?php if( ! $locked ): ?>
        <a href="<?php echo $edit_url; ?>"><?php _e( 'Edit', 'ninja-forms' ); ?></a> |
        <?php else: ?>
        <span><?php _e( 'Edit', 'ninja-forms' ); ?></span> |
        <?php endif; ?>
    </span>


    <span class="trash">
        <a href="<?php echo $delete_url; ?>">Delete</a> |
    </span>

    <span class="duplicate">
        <a href="<?php echo $duplicate_url; ?>">Duplicate</a> |
    </span>

    <span class="bleep">
        <?php if( ! $locked ): ?>
            <a href="<?php echo $preview_url; ?>"><?php _e( 'Preview Form', 'ninja-forms' ); ?></a> |
        <?php else: ?>
            <span><?php _e( 'Preview Form', 'ninja-forms' ); ?></span> |
        <?php endif; ?>
    </span>

    <span class="subs">
        <a target="_blank" href="<?php echo $submissions_url; ?>">View Submissions</a>
    </span>

</div>
