<?php
$this->Breadcrumb->add(__d('admin', 'Upload'), array('controller' => 'upload', 'action' => 'index')); ?>

<div class="action-buttons">
	<?php echo $this->Html->link('<span class="icon-upload"></span> ' . __d('admin', 'View All Uploads'),
		array('controller' => 'crud', 'action' => 'index', 'model' => 'admin.file_upload'),
		array('class' => 'btn btn-primary btn-large', 'escape' => false)); ?>
</div>

<h2><?php echo $model->singularName; ?></h2>

<?php echo $this->Form->create($model->alias, array('class' => 'form-horizontal', 'type' => 'file')); ?>

<div class="alert alert-info">
	<?php echo __d('admin', 'Uploading supports all types of files with no restrictions. Files can also be transported to a remote service. However, only image files will be transformed.'); ?>
	<a href="http://milesj.me/code/cakephp/uploader"><?php echo __d('admin', 'Learn more about the Uploader plugin.'); ?></a>
</div>

<div class="row-fluid">
	<div class="span6">
		<?php
		echo $this->Form->input('path', array('type' => 'file', 'label' => __d('admin', 'File')));
		echo $this->Form->input('caption', array('type' => 'textarea', 'label' => __d('admin', 'Caption'))); ?>

		<fieldset>
			<legend><?php echo __d('admin', 'Transport To'); ?></legend>

			<?php
			echo $this->Form->input('FileUpload.transport.class', array(
				'label' => __d('admin', 'Service'),
				'onchange' => "Admin.toggleUploadField(this, '.transport');",
				'options' => array(
					's3' => __d('admin', 'AWS S3'),
					'glacier' => __d('admin', 'AWS Glacier')
				)
			)); ?>

			<div class="transport s3 glacier">
				<?php
				echo $this->Form->input('FileUpload.transport.accessKey', array('label' => __d('admin', 'Access Key')));
				echo $this->Form->input('FileUpload.transport.secretKey', array('label' => __d('admin', 'Secret Key')));
				echo $this->Form->input('FileUpload.transport.region', array(
					'label' => __d('admin', 'Region'),
					'options' => array(
						'us-east-1' => __d('admin', 'US East - Virginia'),
						'us-west-1' => __d('admin', 'US West - California'),
						'us-west-2' => __d('admin', 'US West 2 - Oregon'),
						'eu-west-1' => __d('admin', 'EU West - Ireland'),
						'ap-southeast-1' => __d('admin', 'AP Southeast - Singapore'),
						'ap-southeast-2' => __d('admin', 'AP Southeast 2 - Sydney'),
						'ap-northeast-1' => __d('admin', 'AP Northeast - Tokyo'),
						'sa-east-1' => __d('admin', 'SA East - Sao Paulo'),
					)
				)); ?>
			</div>

			<div class="transport s3">
				<?php
				echo $this->Form->input('FileUpload.transport.bucket', array('label' => __d('admin', 'Bucket')));
				echo $this->Form->input('FileUpload.transport.folder', array('label' => __d('admin', 'Folder')));
				echo $this->Form->input('FileUpload.transport.scheme', array(
					'label' => __d('admin', 'Scheme'),
					'options' => array(
						'https' => 'HTTPS',
						'http' => 'HTTP'
					)
				));
				echo $this->Form->input('FileUpload.transport.acl', array(
					'label' => __d('admin', 'ACL'),
					'options' => array(
						'public-read' => __d('admin', 'Public Read'),
						'public-read-write' => __d('admin', 'Public Read Write'),
						'authenticated-read' => __d('admin', 'Authenticated Read'),
						'bucket-owner-read' => __d('admin', 'Bucket Owner Read'),
						'bucket-owner-full-control' => __d('admin', 'Bucket Owner Full Control'),
						'private' => __d('admin', 'Private'),
					)
				)); ?>
			</div>

			<div class="transport glacier" style="display: none">
				<?php
				echo $this->Form->input('FileUpload.transport.vault', array('label' => __d('admin', 'Vault')));
				echo $this->Form->input('FileUpload.transport.accountId', array('label' => __d('admin', 'Account ID'))); ?>
			</div>
		</fieldset>
	</div>

	<div class="span6">
		<?php foreach (array(
			'path_large' => __d('admin', 'Resized'),
			'path_thumb' => __d('admin', 'Thumbnail')
		) as $field => $title) {
			$currentValue = !empty($this->request->data['FileUpload']['transforms'][$field]['method']) ? $this->request->data['FileUpload']['transforms'][$field]['method'] : 'resize'; ?>

		<fieldset>
			<legend><?php echo $title; ?></legend>

			<?php
			echo $this->Form->input('FileUpload.transforms.' . $field . '.method', array(
				'label' => __d('admin', 'Method'),
				'onchange' => "Admin.toggleUploadField(this, '.transform');",
				'options' => array(
					'crop' => __d('admin', 'Crop'),
					'resize' => __d('admin', 'Resize'),
					'flip' => __d('admin', 'Flip'),
					'scale' => __d('admin', 'Scale')
				)
			));
			echo $this->Form->input('FileUpload.transforms.' . $field . '.prepend', array('label' => __d('admin', 'Prepend')));
			echo $this->Form->input('FileUpload.transforms.' . $field . '.append', array('label' => __d('admin', 'Append'))); ?>

			<div class="transform resize crop">
				<?php
				echo $this->Form->input('FileUpload.transforms.' . $field . '.width', array('label' => __d('admin', 'Width')));
				echo $this->Form->input('FileUpload.transforms.' . $field . '.height', array('label' => __d('admin', 'Height'))); ?>
			</div>

			<div class="transform resize"<?php if ($currentValue !== 'resize') echo ' style="display: none"'; ?>>
				<?php
				echo $this->Form->input('FileUpload.transforms.' . $field . '.mode', array(
					'label' => __d('admin', 'Mode'),
					'options' => array(
						'width' => __d('admin', 'Width'),
						'height' => __d('admin', 'Height')
					)
				));
				echo $this->Form->input('FileUpload.transforms.' . $field . '.expand', array('label' => __d('admin', 'Allow greater than current dimension'), 'type' => 'checkbox'));
				echo $this->Form->input('FileUpload.transforms.' . $field . '.aspect', array('label' => __d('admin', 'Maintain aspect ratio'), 'type' => 'checkbox')); ?>
			</div>

			<div class="transform crop"<?php if ($currentValue !== 'crop') echo ' style="display: none"'; ?>>
				<?php
				echo $this->Form->input('FileUpload.transforms.' . $field . '.location', array(
					'label' => __d('admin', 'Location'),
					'options' => array(
						'center' => __d('admin', 'Center'),
						'top' => __d('admin', 'Top'),
						'right' => __d('admin', 'Right'),
						'bottom' => __d('admin', 'Bottom'),
						'left' => __d('admin', 'Left')
					)
				)); ?>
			</div>

			<div class="transform flip"<?php if ($currentValue !== 'flip') echo ' style="display: none"'; ?>>
				<?php
				echo $this->Form->input('FileUpload.transforms.' . $field . '.direction', array(
					'label' => __d('admin', 'Direction'),
					'options' => array(
						'vertical' => __d('admin', 'Vertical'),
						'horizontal' => __d('admin', 'Horizontal'),
						'both' => __d('admin', 'Both')
					)
				)); ?>
			</div>

			<div class="transform scale"<?php if ($currentValue !== 'scale') echo ' style="display: none"'; ?>>
				<?php
				echo $this->Form->input('FileUpload.transforms.' . $field . '.percent', array(
					'label' => __d('admin', 'Percent'),
					'type' => 'number'
				)); ?>
			</div>
		</fieldset>

		<?php } ?>
	</div>
</div>

<div class="well actions">
	<button type="submit" class="btn btn-large btn-success">
		<span class="icon-edit icon-white"></span>
		<?php echo __d('admin', 'Upload'); ?>
	</button>
</div>

<?php echo $this->Form->end(); ?>