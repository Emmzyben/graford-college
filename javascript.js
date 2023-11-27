function toggleDropdown() {
    const subMenu = document.querySelector('.sub-menu1');
    subMenu.style.display = (subMenu.style.display === 'none' || subMenu.style.display === '') ? 'block' : 'none';
  }

        let isMenuOpen = false;
        
        const toggleMenu = () => {
            const menu = document.getElementById("ul");
            
            if (!isMenuOpen) {
                menu.style.height = "auto";
                isMenuOpen = true;
            } else {
                menu.style.height = "0px";
                isMenuOpen = false;
            }
        };
        
        const closeMenu = () => {
            const menu = document.getElementById("ul");
            menu.style.height = "0px";
            isMenuOpen = false;
        };
