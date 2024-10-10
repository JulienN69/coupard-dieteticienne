import React, { useState } from "react";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faStar } from "@fortawesome/free-solid-svg-icons";

export default function StarRatingRange() {
	const [star, setStar] = useState(0);
	const [mouseEnter, setMouseEnter] = useState(false);
	const [commentOpen, setCommentOpen] = useState(false); // Pour ouvrir le formulaire

	// Gestion du changement de la valeur du range
	const onChange = (e) => {
		setStar(Number(e.target.value));
	};

	// Survol de l'étoile
	const onMouseEnter = (index) => {
		setMouseEnter(true);
		setStar(index + 1);
		console.log("Survol de l'étoile " + (index + 1));
	};

	// Quitter le survol
	const onMouseLeave = () => {
		setMouseEnter(false);
	};

	// Ouvrir le formulaire de commentaire
	const openForm = () => {
		setCommentOpen(true); // Ouvre le formulaire
	};

	return (
		<div className="form-comment__container">
			<div className="form-comment__title">
				<span className="form-comment__title-span">
					Donnez votre avis
				</span>
				{/* Input range avec gestion du changement */}
				<input
					type="range"
					name="star"
					id="star"
					value={star}
					min="0"
					max="5"
					onChange={onChange} // Ajout du gestionnaire de changement
					style={{ opacity: 0, position: "absolute", zIndex: -1 }}
				/>

				{/* Étoiles */}
				<div className="form-comment__title-stars">
					{[...Array(5)].map((_, index) => (
						<FontAwesomeIcon
							key={index}
							icon={faStar}
							color={index < star ? "#ffc107" : "#e4e5e9"}
							onMouseEnter={() => onMouseEnter(index)}
							onMouseLeave={onMouseLeave}
							onClick={openForm} // Corrige pour ouvrir le formulaire
							size="1.5x"
							style={{ cursor: "pointer" }}
						/>
					))}
				</div>
			</div>

			{/* Formulaire de commentaire qui s'ouvre après un clic */}
			{commentOpen && (
				<div className="form-comment__form">
					<input
						type="text"
						placeholder="pseudo"
						className="form-comment__form-input"
					/>
					<textarea
						placeholder="Entrez votre commentaire"
						className="form-comment__form-textarea"
					/>
					<button className="form-comment__form-button">
						Envoyer votre commentaire
					</button>
				</div>
			)}
		</div>
	);
}
