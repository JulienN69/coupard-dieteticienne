import React, { useEffect } from "react";
import usePaginatedFetch from "../hooks/usePaginatedFetch";
import PropTypes from "prop-types";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faStar } from "@fortawesome/free-solid-svg-icons";
import SectionList from "../components/SectionList";
import FormComment from "../components/FormComment";

const dateFormat = {
	dateStyle: "medium",
	timeStyle: "short",
};

export default function Comments({ id }) {
	const { data, error, loading, fetchData } = usePaginatedFetch(
		`/api/get/recipes/${id}/comments`
	);

	if (error) {
		return <div>Erreur : {error}</div>;
	}

	useEffect(() => {
		fetchData();
	}, []);

	return (
		<section className="comments-section">
			<div className="comments-section__div">
				<SectionList
					title={`Commentaires (${data.length})`}
					className="recipe-detail__comments"
				/>
				<FormComment recipe={id} />
				<div className="comments">
					{loading && <p>Chargement...</p>}
					{!loading &&
						data.length > 0 &&
						data.map((com) => {
							return (
								<div key={com.id} className="comment">
									<h4 className="comment__pseudo">
										{com.pseudo}
									</h4>
									<div className="comment__date">
										<span>commenté le</span>
										<span>
											{new Date(com.date).toLocaleString(
												undefined,
												dateFormat
											)}
										</span>
									</div>
									<div className="comment__stars">
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

Comments.propTypes = {
	id: PropTypes.number.isRequired,
};
