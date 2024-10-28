import React, { useCallback, useRef, useState } from "react";
import StarRating from "./StarRating";
import { useFetch } from "../hooks/useFetch";
import PropTypes from "prop-types";

const className = (...arr) => arr.filter(Boolean).join(" ");

export default function FormComment({ error, recipe, onComment }) {
	const [commentOpen, setCommentOpen] = useState(false);
	const [rating, setRating] = useState(0);

	// Ouvrir le formulaire de commentaire quand une étoile est cliquée
	const handleRatingSelect = (selectedRating) => {
		setRating(selectedRating);
		setCommentOpen(true);
	};

	const onSuccess = useCallback(
		(comment) => {
			onComment(comment);
			pseudoRef.current.value = "";
			commentRef.current.value = "";
			clearError(errors);
		},
		[onComment]
	);

	const pseudoRef = useRef(null);
	const commentRef = useRef(null);
	const { load, errors, loading, clearError } = useFetch(
		"/api/recipe/commentss",
		"POST",
		onSuccess
	);

	const onSubmit = useCallback(
		(e) => {
			e.preventDefault();
			load({
				pseudo: pseudoRef.current.value,
				comment: commentRef.current.value,
				note: rating,
				date: new Date(),
				recipe: `/api/recipes/${recipe}`,
			});
		},
		[load, pseudoRef, commentRef, rating, recipe]
	);

	return (
		<form onSubmit={onSubmit} className="form-comment">
			<div className="form-comment__title">
				<span className="form-comment__title-span">
					Donnez votre avis
				</span>
				<StarRating onRatingSelect={handleRatingSelect} />
			</div>

			{commentOpen && (
				<div className="form-comment__form">
					<input
						type="text"
						placeholder="pseudo"
						className="form-comment__form-input"
						name="pseudo"
						ref={pseudoRef}
						required
						onChange={clearError.bind(this, "pseudo")}
					/>
					{errors["pseudo"] && (
						<div className="form-comment__form-error">
							{errors["pseudo"]}
						</div>
					)}
					<Field
						help="Les commentaires non conformes à notre règle de conduite
					seront modérés"
						ref={commentRef}
						error={errors["comment"]}
						name="comment"
						onChange={clearError.bind(this, "comment")}
						required
					/>
					<button
						type="submit"
						className="form-comment__form-button"
						disabled={loading}
					>
						Envoyer votre commentaire
					</button>
				</div>
			)}
		</form>
	);
}

FormComment.propTypes = {
	error: PropTypes.bool, // booléen pour indiquer s'il y a une erreur générale
	recipe: PropTypes.oneOfType([PropTypes.string, PropTypes.number])
		.isRequired, // ID de la recette sous forme de chaîne ou nombre
	onComment: PropTypes.func.isRequired, // Fonction de rappel lorsque le commentaire est envoyé
};

const Field = React.forwardRef(
	({ help, name, children, error, onChange }, ref) => {
		if (error) {
			help = error;
		}
		return (
			<div
				className={className(
					"form-comment__form-div",
					error && "has-error"
				)}
			>
				<label htmlFor={name}>{children}</label>
				<textarea
					placeholder="Entrez votre commentaire"
					className={className(
						"form-comment__form-textarea",
						error && "has-error"
					)}
					ref={ref}
					name={name}
					id={name}
					onChange={onChange}
					required
				/>
				{help && (
					<div
						className={className(
							"form-comment__form-textarea-help",
							error && "has-error"
						)}
					>
						{help}
					</div>
				)}
			</div>
		);
	}
);

Field.propTypes = {
	help: PropTypes.string, // Texte d'aide pour le champ
	name: PropTypes.string.isRequired, // Nom du champ (identifiant unique)
	children: PropTypes.node, // Enfant de label, ici pour le texte du label
	error: PropTypes.string, // Message d'erreur (ou texte à afficher en cas d’erreur)
	onChange: PropTypes.func.isRequired, // Fonction de rappel pour les changements dans le textarea
};
