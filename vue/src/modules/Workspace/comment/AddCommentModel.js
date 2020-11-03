import BaseModel from "../../../core/components/input-widget/BaseModel";

export default class AddCommentModel extends BaseModel {
  comment = '';
  article_id = null;
  timeline_post_id = null;
  parent_id = null;
}