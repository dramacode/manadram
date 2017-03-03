<div class="filter">
    <label class="date" >Création entre </label>
    <select class="date" name="filters[from]">
        <option></option>
        <?php foreach($filters["lustrum"] as $lustrum){
            //$lustrum = substr($lustrum,0,4);
            ?>
        <option value=<?php echo $lustrum; ?> <?php if(isset($_GET["filters"]["from"])){if($lustrum == $_GET["filters"]["from"]){echo "selected";}}?>><?php echo $lustrum;?></option>
        <?php } ?>    
        
    </select>
    <label class="date" > et </label>
    
    <select class="date" name="filters[to]">
        <option></option>
        <?php foreach($filters["lustrum"] as $lustrum){
            //$lustrum = substr($lustrum,5,4);
            ?>
        <option value=<?php echo $lustrum; ?> <?php if(isset($_GET["filters"]["to"])){if($lustrum == $_GET["filters"]["to"]){echo "selected";}}?> ><?php echo $lustrum;?></option>
        <?php } ?>    
    </select>
</div>
<div class="filter">
    <label class="text" >Genre</label>
    <select class="text" name="filters[genre][]" multiple>
            <option></option>
    
        <?php foreach($filters["genre"] as $genre){?>
        <option value="<?php echo $genre;?>" <?php if(isset($_GET["filters"]["genre"])){if(in_array($genre, $_GET["filters"]["genre"])){echo "selected";}}?>><?php echo $genre;?></option>
        <?php } ?>
    </select>
</div>
<div class="filter">
    <label class="text" >Auteur</label>
    <select class="text" name="filters[author][]" multiple>
            <option></option>
    
        <?php foreach($filters["author"] as $author){?>
        <option value="<?php echo $author;?>" <?php if(isset($_GET["filters"]["author"])){if(in_array($author, $_GET["filters"]["author"])){echo "selected";}}?>><?php echo $author;?></option>
        <?php } ?>
    </select>
</div>
<div class="filter">
    <label class="text" >Titre</label>
    <select class="text"  name="filters[code][]" multiple>
            <option></option>
    
        <?php foreach($filters["code"] as $key=>$code){?>
        <option value="<?php echo $key;?>" <?php if(isset($_GET["filters"]["code"])){if(in_array($key, $_GET["filters"]["code"])){echo "selected";}}?>><?php echo $code["author"];?>, <i><?php echo $code["title"];?></i></option>
        <?php } ?>
    </select>
</div>