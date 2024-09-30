import React from "react";
import StarRatingRange from "../components/StarRatingRange";
import usePaginatedFetch from "../hooks/usePaginatedFetch";
import PropTypes from "prop-types";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faStar } from "@fortawesome/free-solid-svg-icons";

export default function Comments({ id }) {
	const { data, error, loading, fetchData } = usePaginatedFetch(
		`/api/get/recipes/${id}/comments`
	);

	if (error) {
		return <div>Erreur : {error}</div>;
	}

	return (
		<section className="comments-section">
			<div className="comments-section__div">
				<FormComments />
				<div className="comments">
					<button onClick={fetchData}>
						Afficher les commentaires
					</button>
					{loading && <p>Chargement...</p>}

					{!loading &&
						data.length > 0 &&
						data.map((com) => {
							return (
								<div key={com.id} className="comment">
									<h4 className="comment__pseudo">
										{com.pseudo}
									</h4>
									<div className="form-comment__title-stars">
										{[...Array(5)].map((_, index) => (
											<FontAwesomeIcon
												key={index}
												icon={faStar}
												color={
													index < com.note
														? "#ffc107"
														: "#e4e5e9"
												}
												size="1x"
											/>
										))}
									</div>
									<span className="comment__text">
										"{com.comment}"
									</span>
								</div>
							);
						})}
				</div>
			</div>
		</section>
	);
}

function FormComments() {
	return (
		<form action="submit" method="post" className="form-comment">
			<StarRatingRange />
		</form>
	);
}

Comments.propTypes = {
	id: PropTypes.number.isRequired,
};
