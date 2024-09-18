import React, { useState } from "react";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faBars, faXmark } from "@fortawesome/free-solid-svg-icons";

export default function Navbar() {
	const [isOpen, setIsOpen] = useState(false);

	function openSidebar() {
		return setIsOpen(true);
	}
	function closeSidebar() {
		return setIsOpen(false);
	}

	return (
		<div className={`sidebar ${isOpen ? "light" : "dark"}`}>
			<nav className={`sidebar__nav ${isOpen ? "active" : "inactive"}`}>
				<span>Sandrine Coupard</span>
				<a href="#">Home</a>
				<a href="#">About</a>
				<a href="#">Services</a>
				<a href="#">Contact</a>
				<a href="#">Recettes</a>
				<a href="#">Connexion</a>
			</nav>
			<div className={`sidebar__btn ${isOpen ? "darken" : "lighten"}`}>
				<button
					type="button"
					aria-label="Close menu"
					onClick={isOpen ? closeSidebar : openSidebar}
					className={`${isOpen ? "hamburgerBtn" : "closeBtn"}`}
				>
					<div>
						{isOpen ? (
							<FontAwesomeIcon icon={faXmark} />
						) : (
							<FontAwesomeIcon icon={faBars} />
						)}
					</div>
				</button>
			</div>
		</div>
	);
}
