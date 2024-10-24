import React, { useState } from "react";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faStar } from "@fortawesome/free-solid-svg-icons";

export default function StarRating({ onRatingSelect }) {
	const [star, setStar] = useState(0);
	const [mouseEnter, setMouseEnter] = useState(false);

	// Gestion du changement de la valeur de la note
	const onChange = (index) => {
		setStar(index + 1);
		if (onRatingSelect) {
			onRatingSelect(index + 1); // Envoie la note sélectionnée au composant parent
		}
	};

	// Survol de l'étoile
	const onMouseEnter = (index) => {
		setMouseEnter(true);
		setStar(index + 1);
	};

	// Quitter le survol
	const onMouseLeave = () => {
		setMouseEnter(false);
	};

	return (
		<div className="form-comment__title-stars">
			<input type="hidden" name="rating" value={star} />

			{/* Affichage des étoiles */}
			{[...Array(5)].map((_, index) => (
				<FontAwesomeIcon
					key={index}
					icon={faStar}
					color={index < star ? "#ffc107" : "#e4e5e9"}
					onMouseEnter={() => onMouseEnter(index)}
					onMouseLeave={onMouseLeave}
					onClick={() => onChange(index)}
					size="1.5x"
					style={{ cursor: "pointer" }}
				/>
			))}
		</div>
	);
}
