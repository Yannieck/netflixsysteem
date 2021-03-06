<div class="filter">
    <div class="flex-container">
        <!-- Remove filter: -->
        <div class="logos" onclick="redirect(0)">
            <i class="removeIcon fas fa-times"></i>
            <p>Remove filter</p>
        </div>
        <!-- Andere tags: -->
        <div class="logos" onclick="redirect(5)">
            <img src="../assets/icons/HTML.svg" alt="HTML">
            <p>&lt;HTML&sol;&gt;</p>
        </div>
        <div class="logos" onclick="redirect(6)">
            <img src="../assets/icons/PHP.svg" alt="php">
            <p>&lt;?php</p>
        </div>
        <div class="logos" onclick="redirect(7)">
            <img src="../assets/icons/JavaScript.svg" alt="JavaScript">
            <p>Javascript;</p>
        </div>
        <div class="logos" onclick="redirect(8)">
            <img src="../assets/icons/CSharp.svg" alt="C#">
            <p>C#</p>
        </div>
        <div class="logos" onclick="redirect(4)">
            <img src="../assets/icons/CSS.svg" alt="CSS">
            <p>CSS</p>
        </div>
        <div class="logos" onclick="redirect(2)">
            <img src="../assets/icons/Java.svg" alt="Java">
            <p>Java</p>
        </div>
        <div class="logos" onclick="redirect(1)">
            <img src="../assets/icons/Python.svg" alt="Python">
            <p>Python</p>
        </div>
        <div class="logos" onclick="redirect(9)">
            <img src="../assets/icons/Android.svg" alt="Android">
            <p>Android</p>
        </div>
        <div class="logos" onclick="redirect(10)">
            <img src="../assets/icons/Apple.svg" alt="Apple">
            <p>Apple</p>
        </div>
        <div class="logos" onclick="redirect(3)">
            <img src="../assets/icons/Cplusplus.svg" alt="C++">
            <p>C++</p>
        </div>
    </div>
</div>
<script>
    // Als de remove filter is ingedrukt (filterIndex == 0): verwijder de get,
    // Is elke andere knop ingedrukt (filterIndex > 0): stop de index in de link
    function redirect(filterIndex) {
        if (filterIndex > 0) {
            window.location.href = "./main.php?tag=" + filterIndex;
        } else {
            window.location.href = "./main.php";
        }
    }
</script>