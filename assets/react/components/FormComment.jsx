import React, { useCallback, useRef, useState } from "react";
import StarRating from "./StarRating";
import { useFetch } from "../hooks/useFetch";

const className = (...arr) => arr.filter(Boolean).join(" ");

export default function FormComment({ error, recipe }) {
	const [commentOpen, setCommentOpen] = useState(false);
	const [rating, setRating] = useState(0);

	// Ouvrir le formulaire de commentaire quand une étoile est cliquée
	const handleRatingSelect = (selectedRating) => {
		setRating(selectedRating);
		setCommentOpen(true);
	};

	const pseudoRef = useRef(null);
	const commentRef = useRef(null);
	const { load, errors, loading } = useFetch("/api/recipe/commentss", "POST");

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
				<div
					className={className(
						"form-comment__form",
						error && "has-error"
					)}
				>
					<input
						type="text"
						placeholder="pseudo"
						className="form-comment__form-input"
						name="pseudo"
						ref={pseudoRef}
					/>
					<Field
						help="Les commentaires non conformes à notre règle de conduite
					seront modérés"
						ref={commentRef}
						error={errors["content"]}
						name="comment"
					/>
					<button
						type="submit"
						className={className(
							"form-comment__form-button",
							loading && "disabled"
						)}
						disabled={loading}
					>
						Envoyer votre commentaire
					</button>
				</div>
			)}
		</form>
	);
}

const Field = React.forwardRef(
	({ help, name, children, error, onChange }, ref) => {
		if (error) {
			help = error;
		}
		return (
			<div
				className={className(
					"form-comment__form-textarea",
					error && "has-error"
				)}
			>
				<label htmlFor={name}>{children}</label>
				<textarea
					placeholder="Entrez votre commentaire"
					className="form-comment__form-textarea"
					ref={ref}
					name={name}
					id={name}
					onChange={onChange}
				/>
				{help && <div>{help}</div>}
			</div>
		);
	}
);
