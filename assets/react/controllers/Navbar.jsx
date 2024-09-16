import React, { useState } from "react";

export default function Navbar() {
	const [isOpen, setIsOpen] = useState(true);

	function openSidebar() {
		return setIsOpen(true);
	}
	function closeSidebar() {
		return setIsOpen(false);
	}

	return (
		<div className={`sidebar ${isOpen ? "active" : "inactive"}`}>
			<nav className="sidebar__nav">
				<a href="#">Sandrine Coupard</a>
				<a href="#">Home</a>
				<a href="#">About</a>
				<a href="#">Services</a>
				<a href="#">Contact</a>
				<a href="#">Recettes</a>
				<a href="#">Connexion</a>
			</nav>
			<div className="sidebar__btn">
				<button
					type="button"
					aria-label="Close menu"
					onClick={closeSidebar}
				>
					<div>&#215;</div>
				</button>
			</div>
		</div>
	);
}
