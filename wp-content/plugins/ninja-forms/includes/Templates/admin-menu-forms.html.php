<div id="nf-builder">
    <div id="nf-main">
        <!-- main content area. Where fields and actions are rendered. -->

        <?php
        for ($i=0; $i < 25; $i++) {
            echo '<div class="nf-field-wrap">Field</div>';
        }
        ?>

    </div>

    <div id="nf-drawer">
        <!-- drawer area. This is where settings and add fields are rendered. -->
        <!-- THIS IS THE CONTENT FOR EDITING FIELDS -->
        <header class="nf-full">
            <h2>Editing Field</h2>
            <span><input type="submit" class="save-field-settings" value="SAVE" /></span>
        </header>
        <div class="nf-one-half">
            <label>Label Name</label>
            <input type="text" />
        </div>
        <div class="nf-one-half">
            <label>Label Position</label>
            <select>
                <option>Above Field</option>
                <option>Below Field</option>
                <option>Left of Field</option>
                <option>Right of Field</option>
                <option>Hide Label</option>
            </select>
        </div>
        <div class="nf-full">
            <label>Class Name</label>
            <input type="text" />
        </div>
    </div>

    <!-- THIS IS THE CONTENT FOR ADDING FIELDS
    <header class="nf-full">
        <input type="search" />
        <span><input type="submit" class="close-add-fields" value="DONE" /></span>
    </header>

    <div class="nf-reservoir">
        <span>Textbox</span><span>Textbox</span><span>Textbox</span><span>Textbox</span><span>Textbox</span><span>Textbox</span><span>Textbox</span><span>Textbox</span><span>Textbox</span>
    </div>

    <h3>Basic Fields</h3>

    <div class="nf-one-third">
        <div class="nf-field-button">Textbox</div>
    </div>
    <div class="nf-one-third">
        <div class="nf-field-button">Textarea</div>
    </div>
    <div class="nf-one-third">
        <div class="nf-field-button">Checkbox</div>
    </div>
    <div class="nf-one-third">
        <div class="nf-field-button">Dropdown</div>
    </div>
    <div class="nf-one-third">
        <div class="nf-field-button">Mult-Select</div>
    </div>
    <div class="nf-one-third">
        <div class="nf-field-button">Radio List</div>
    </div>
    <div class="nf-one-third">
        <div class="nf-field-button">Hidden Field</div>
    </div>
    <div class="nf-one-third">
        <div class="nf-field-button">Button</div>
    </div>


    <h3>Basic Fields</h3>

    <div class="nf-one-third">
        <div class="nf-field-button">Textbox</div>
    </div>
    <div class="nf-one-third">
        <div class="nf-field-button">Textarea</div>
    </div>
    <div class="nf-one-third">
        <div class="nf-field-button">Checkbox</div>
    </div>
    <div class="nf-one-third">
        <div class="nf-field-button">Dropdown</div>
    </div>
    <div class="nf-one-third">
        <div class="nf-field-button">Mult-Select</div>
    </div>
    <div class="nf-one-third">
        <div class="nf-field-button">Radio List</div>
    </div>
    <div class="nf-one-third">
        <div class="nf-field-button">Hidden Field</div>
    </div>
    <div class="nf-one-third">
        <div class="nf-field-button">Button</div>
    </div>

    <div class="nf-full">
        <h3>Basic Fields</h3>
    </div>
    <div class="nf-one-third">
        <div class="nf-field-button">Textbox</div>
    </div>
    <div class="nf-one-third">
        <div class="nf-field-button">Textarea</div>
    </div>
    <div class="nf-one-third">
        <div class="nf-field-button">Checkbox</div>
    </div>
    <div class="nf-one-third">
        <div class="nf-field-button">Dropdown</div>
    </div>
    <div class="nf-one-third">
        <div class="nf-field-button">Mult-Select</div>
    </div>
    <div class="nf-one-third">
        <div class="nf-field-button">Radio List</div>
    </div>
    <div class="nf-one-third">
        <div class="nf-field-button">Hidden Field</div>
    </div>
    <div class="nf-one-third">
        <div class="nf-field-button">Button</div>
    </div>

    <div class="nf-full">
        <h3>Basic Fields</h3>
    </div>
    <div class="nf-one-third">
        <div class="nf-field-button">Textbox</div>
    </div>
    <div class="nf-one-third">
        <div class="nf-field-button">Textarea</div>
    </div>
    <div class="nf-one-third">
        <div class="nf-field-button">Checkbox</div>
    </div>
    <div class="nf-one-third">
        <div class="nf-field-button">Dropdown</div>
    </div>
    <div class="nf-one-third">
        <div class="nf-field-button">Mult-Select</div>
    </div>
    <div class="nf-one-third">
        <div class="nf-field-button">Radio List</div>
    </div>
    <div class="nf-one-third">
        <div class="nf-field-button">Hidden Field</div>
    </div>
    <div class="nf-one-third">
        <div class="nf-field-button">Button</div>
    </div>

    <div class="nf-full">
        <h3>Basic Fields</h3>
    </div>
    <div class="nf-one-third">
        <div class="nf-field-button">Textbox</div>
    </div>
    <div class="nf-one-third">
        <div class="nf-field-button">Textarea</div>
    </div>
    <div class="nf-one-third">
        <div class="nf-field-button">Checkbox</div>
    </div>
    <div class="nf-one-third">
        <div class="nf-field-button">Dropdown</div>
    </div>
    <div class="nf-one-third">
        <div class="nf-field-button">Mult-Select</div>
    </div>
    <div class="nf-one-third">
        <div class="nf-field-button">Radio List</div>
    </div>
    <div class="nf-one-third">
        <div class="nf-field-button">Hidden Field</div>
    </div>
    <div class="nf-one-third">
        <div class="nf-field-button">Button</div>
    </div>
    -->
</div>
